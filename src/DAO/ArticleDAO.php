<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 15/03/2017
 * Time: 13:57
 */

namespace MicroCMS\DAO;


use Doctrine\DBAL\Connection;
use MicroCMS\Domain\Article;

class ArticleDAO extends DAO
{
    public function find($id)
    {
        $sql = "select * from t_article where art_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            throw new \Exception("No article matching id " . $id);
        }
    }

    /**
     * Return a list of all articles, sorted by date (most recent first)
     * @return array
     */
    public function findAll()
    {
        $sql = "select * from t_article order by art_id desc";
        $result = $this->getDb()->fetchAll($sql);

        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }

        return $articles;
    }

    /**
     * Saves an article into the database
     * @param Article $article
     */
    public function save(Article $article)
    {
        $articleData = array(
            'art_title' => $article->getTitle(),
            'art_content' => $article->getContent()
        );

        if($article->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('t_article', $articleData, array(
                'art_id' => $article->getId()
            ));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('t_article', $articleData);
            // Get the id of the newly created article and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);
        }
    }

    /**
     * Removes an article from the database
     * @param $id
     */
    public function delete($id)
    {
        // Delete the article
        $this->getDb()->delete('t_article', array('art_id' => $id ));
    }

    /**
     * Creates an article object based on a db row
     * @param array $row
     * @return Article
     */
    protected function buildDomainObject(array $row)
    {
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);

        return $article;
    }

}
