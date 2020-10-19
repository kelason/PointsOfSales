<?php

class ProductCategories extends Database
{
    public $product_id;
    public $category_id;
    private $table = "posproduct_categories";

    public function createProductCategories()
    {
        $fields = array(
            'product_id'  => $this->product_id,
            'category_id'  => $this->category_id
        );

        return $this->create($this->table, $fields);
    }
}