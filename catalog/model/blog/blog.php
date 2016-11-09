<?php
class ModelBlogBlog extends Model {

    public function getCategories() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category");

        return $query->rows;
    }
    public function getPosts($sort = 'sort_order',$order = 'ASC') {
        $query = $this->db->query("SELECT bp.*,bpd.* FROM " . DB_PREFIX . "blog_post bp 
            INNER JOIN " . DB_PREFIX . " blog_post_description bpd ON bp.blog_post_id = bpd.blog_post_id 
            WHERE bp.status > '0' ORDER BY bp." . $sort . " " . $order);

        return $query->rows;
    }
    public function getPostsById($category_id) {
        $query = $this->db->query("SELECT bp.*,bpd.* FROM " . DB_PREFIX . "blog_post bp INNER JOIN " . DB_PREFIX . " blog_post_description bpd ON bp.blog_post_id=bpd.blog_post_id INNER JOIN " . DB_PREFIX . " blog_category bc ON bp.blog_category_id=bc.id WHERE bc.id=".$category_id."");

        return $query->rows;
    }
    public function getPostsByAuthors() {
        $sql = "SELECT DISTINCT(author.id) ,author.username FROM " . DB_PREFIX . "blog_post bp INNER JOIN " . DB_PREFIX . " user author ON bp.created_by=author.id GROUP BY bp.sort_order,author.id,author.username";

        $query = $this->db->query($sql);
        return $query->rows;
    }
    public function getAuthors() {
        $sql="SELECT * FROM " . DB_PREFIX . "blog_author ORDER BY sort_order LIMIT 3";

        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getAuthorId($author_id) {
        $sql="SELECT *,image as im FROM " . DB_PREFIX . "blog_author WHERE author_id = ".(int) $author_id;

        $query = $this->db->query($sql);

        return $query->row;
    }
    public function getAllAuthors() {
        $sql="SELECT * FROM " . DB_PREFIX . "blog_author ORDER BY sort_order, created_at DESC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAuthorsPosts($author_id) {
        $sql="SELECT COUNT(*) as Total FROM " . DB_PREFIX . "blog_post bp INNER JOIN ".DB_PREFIX." blog_author ba ON bp.`author_id`=ba.`author_id` WHERE ba.author_id='".$author_id."'";

        $query = $this->db->query($sql);
        return $query->row['Total'];
    }
    public function getPostsDescriptions($post_id) {
        $sql="SELECT au.first_name,au.last_name,bp.blog_post_id AS post_id, au.author_id,bp.blog_category_id AS category_id,bp.image,bp.thumb,bp.created_at AS date_craeted,bpd.* FROM " . DB_PREFIX . "blog_post bp INNER JOIN " . DB_PREFIX . " blog_post_description bpd ON bp.blog_post_id=bpd.blog_post_id INNER JOIN " . DB_PREFIX . " blog_category bc ON bp.blog_category_id=bc.id INNER JOIN " . DB_PREFIX . " blog_author au ON bp.author_id=au.author_id WHERE bp.blog_post_id=".$post_id;

        $query = $this->db->query($sql);

        return $query->row;
    }
    public function getPostsByDate($type) {

        $sql = "SELECT bp.*,bpd.* FROM " . DB_PREFIX . "blog_post bp INNER JOIN " . DB_PREFIX . " blog_post_description bpd ON bp.blog_post_id=bpd.blog_post_id INNER JOIN " . DB_PREFIX . " blog_category bc ON bp.blog_category_id=bc.id WHERE bp.date_added > DATE_SUB(NOW(), INTERVAL 1 ".$type.")";

        $query=$this->db->query($sql);

        return $query->rows;
    }
    public function getPostsByAuthor($author_id) {
        $sql = "SELECT bp.*,bpd.* FROM " . DB_PREFIX . "blog_post bp INNER JOIN " . DB_PREFIX . " blog_post_description bpd ON bp.blog_post_id=bpd.blog_post_id INNER JOIN " . DB_PREFIX . " blog_category bc ON bp.blog_category_id=bc.id WHERE bp.author_id=".(int)$author_id." ORDER BY bp.sort_order";

        $query=$this->db->query($sql);

        return $query->rows;
    }
}

?>