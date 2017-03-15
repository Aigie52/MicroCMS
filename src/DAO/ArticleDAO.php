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

class ArticleDAO
{
    /**
     * Database connection
     * @var Connection
     */
    private $db;

    /**
     * ArticleDAO constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this-> db = $db;
    }

    /**
     * Return a list of all articles, sorted by date (most recent first)
     * @return array
     */
    public function findAll()
    {
        $sql = "select * from t_article order by art_id desc";
        $result = $this->db->fetchAll($sql);

        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildArticle($row);
        }

        return $articles;
    }

    /**
     * Creates an article object based on a db row
     * @param array $row
     * @return Article
     */
    private function buildArticle(array $row)
    {
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);

        return $article;
    }

}
