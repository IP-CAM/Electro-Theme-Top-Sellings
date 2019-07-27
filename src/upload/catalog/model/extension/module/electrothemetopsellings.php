<?php
/** 
 * 
 * electrothemetopsellings.php
 * 
 * ModelExtensionModuleElectrothemetopsellings
 * 
 * Model file for a front page.
 * 
*/
class ModelExtensionModuleElectrothemetopsellings extends Model
{
    private function getMultiplexor($currency){
        $temp=$this->db->query("SELECT * FROM ".DB_PREFIX."currency WHERE code='".$currency."'");
        $multiplexor=$temp->row['value'];
        if (!isset($multiplexor) || !is_numeric($multiplexor) || ($multiplexor <= 0)){
            $multiplexor=1;
        }
        return $multiplexor;
    }
    /**
     * 
     * getCategories()
     * 
     * @param   null
     * 
     * @return  <array> array of categories
     * 
     */
    public function getCategories(){
        $this->load->model("catalog/category");
        $categories=$this->model_catalog_category->getCategories();
        return $categories;
    }
    /** 
     * 
     * getProducts()
     * 
     * @param   null
     * 
     * @return  $data['categories'] array of products
     * 
    */
    public function getProducts($quantity,$currency){
        $data=array(
            "products"=>array()
        );
        // Getting current currency
        $multiplexor=$this->getMultiplexor($currency);

        $this->load->model("catalog/product");
        
        $data['products']=$this->model_catalog_product->getBestSellerProducts($quantity);
        foreach ($data['products'] as &$d){

            $d['price']=$this->currency->format($this->tax->calculate($d['price'], $d['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

            if ($d['special']){
                        
                $d['special'] = $this->currency->format($this->tax->calculate($d['special'], $d['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

            }
            

            $p_categories=$this->model_catalog_product->getCategories($d['product_id']);

            $d['category_id']=array();
            foreach ($p_categories as $p_cat){
                $d['category_id'][]=$p_cat['category_id'];
            }
        }

        $data['categories']=$this->filterCategories($data['products']);
        return $data;
    }
   
    /** 
     * 
     * filterCategories()
     * 
     * @param   $products  
     * 
     * @return  $filtered_categories
     *  
    */
    protected function filterCategories($products){
        $this->load->model("catalog/category");
        $filtered_categories=array();

        $categories=$this->model_catalog_category->getCategories();
        $f_cat=array();
        foreach ($products as $product){
            $p_category=$product['category_id'];
            foreach ($p_category as $p){
                if (!in_array($p,$f_cat)){
                    $f_cat[]=$p;   // category_id
                }
            }
        }

        foreach ($f_cat as $c){
            foreach ($categories as $category){
                if ($category['category_id'] == $c){
                    $filtered_categories[]=array(
                        "name"=>$category['name'],
                        "id"=>$category['category_id']
                    );
                }
            }
        }

        return $filtered_categories;
    }
}