<?php
include_once("helpers/commentHelper.php");
function getCommentTree()
{
    global $Link;
    $commentId = trim(getParams("id"));
    if(!checkExistComment($commentId))
    {
        setHTTPStatus("404","There is no such comment that was passed to id. Try checking the data.");
        return;
    }
    $commetTreeQuery = $Link->query("
        WITH RECURSIVE CommentTree AS (
            SELECT 
                id, 
                createTime, 
                content, 
                modifiedDate, 
                deleteDate, 
                authorId, 
                author, 
                subComments
            FROM comment
            WHERE id = '$commentId' AND isDelete = false
            UNION
            SELECT 
                c.id, 
                c.createTime, 
                c.content, 
                c.modifiedDate, 
                c.deleteDate, 
                c.authorId, 
                c.author, 
                c.subComments
            FROM comment c
            JOIN CommentTree ct ON c.parentId = ct.id
            WHERE c.isDelete = false
        )
        SELECT 
            id, 
            createTime, 
            content, 
            modifiedDate, 
            deleteDate, 
            authorId, 
            author, 
            subComments
        FROM CommentTree
        WHERE id <> '$commentId';
    ");
    if($commetTreeQuery)
    {
        $commetTreeResult = $commetTreeQuery->fetch_all(MYSQLI_ASSOC);
        echo json_encode($commetTreeResult);
    }
    else
    {
        setHTTPStatus("400","Error when trying to get comments".$Link->error);
    }
}