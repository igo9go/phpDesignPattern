<?php
/**
 * 建造者模式
 * 解决问题：消除其他对象的复杂创建过程，这是最佳做法，而且在对象的构造和配置方法改变时，可以尽可能的减少重复更改代码。
 */

class product
{
    protected $_type = "";
    protected $_size = "";
    protected $_color = "";
    
    //假设有三个复杂的创建过程
    public function setType($type)
    {
        $this->_type = $type;
    }
    public function setSize($size)
    {
        $this->_size = $size;
    }
    public function setColor($color)
    {
        $this->_color = $color;
    }
}

$productConfigs = array('type'=>'shirt','size'=>'XL','color'=>'red');

$product = new product();
//创建对象时分别调用每个方法不是最佳做法，扩展和可适应性低
$product->setType($productConfigs['type']);
$product->setSize($productConfigs['size']);
$product->setColor($productConfigs['color']);
//复杂的创建过程中使用构造函数来实现更不可取。


//建造者模式
class productBuilder
{
    protected $_product = null;
    protected $_configs = array();

    public function __construct($configs)
    {
        $this->_product = new product();
        $this->_configs = $configs; 
    }

    public function build()
    {
        $this->_product->setSize($this->_configs['size']);
        $this->_product->setType($this->_configs['type']);
        $this->_product->setColor($this->_configs['color']);
    }

    public function getProduct()
    {
        return $this->_product;
    }
}

$builder = new productBuilder($productConfigs);
$builder->build();
$product = $builder->getProduct();

