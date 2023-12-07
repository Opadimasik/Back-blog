<?php
include_once("helpers/commentHelper.php");
function getCommentTree($commentId)
{
    global $Link;
    
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
            WHERE id = '$commentId'
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
    //
    //WHERE c.isDelete = false
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