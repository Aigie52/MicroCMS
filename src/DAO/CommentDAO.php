<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 15/03/2017
 * Time: 16:09
 */

namespace MicroCMS\DAO;


use MicroCMS\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var ArticleDAO
     */
    private $articleDAO;

    /**
     * @var UserDAO
     */
    private $userDAO;

    /**
     * @param ArticleDAO $articleDAO
     */
    public function setArticleDAO(ArticleDAO $articleDAO)
    {
        $this->articleDAO = $articleDAO;
    }

    /**
     * @param UserDAO $userDAO
     */
    public function setUserDAO(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }


    /**
     * @param $articleId
     * @return array
     */
    public function findAllByArticle($articleId)
    {
        $article = $this->articleDAO->find($articleId);

        $sql = "select * from t_comment where art_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleId));

        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            $comment->setArticle($article);

            $comments[$comId] = $comment;
        };

        return $comments;
    }

    /**
     * @param array $row
     * @return Comment
     */
    protected function buildDomainObject(array $row)
    {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('art_id', $row)) {
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }

        if (array_key_exists('usr_id', $row)) {
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }

        return $comment;
    }

    /**
     * Save a comment into the database
     * @param Comment $comment
     */
    public function save(Comment $comment)
    {
        $commentData = array(
            'art_id' => $comment->getArticle()->getId(),
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
        );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array(
                'com_id' => $comment->getId()
            ));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }
}
