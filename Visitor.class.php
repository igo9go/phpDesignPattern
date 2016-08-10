<?php
/**
 * 访问者模式：表示一个作用于某对象结构中的各元素的操作。它可以在不改变各元素的类的前提下
 * 定义作用于这些元素的新操作。

 * 我们来简单分析一下电商的客户，可能有企业客户还有个人用户。现在我们需要分析一下每个客户的
 * 购买偏好。并且不想把具体的实现封装在具体的元素对象中，因为实际上不可能只有两种客户，可能很多，
 * 如果我要添加一个通用的功能，把具体功能封装在对象中，太不便于管理了。
 * 对企业客户和个人客户的请求做不同处理。
 */

/**
 *客户抽象接口
 */
abstract class Customer {
    protected $customerId;

    protected $customerName;
    public abstract function accept( ServiceRequestVisitor $visitor );
}

class EnterpriseCustomer extends Customer {
    public function accept( ServiceRequestVisitor $visitor ) {
        $visitor->visitEnerpriseCustomer($this);
    } 
}

class PersonalCustomer extends Customer {
    public function accept( ServiceRequestVisitor $visitor ) {
        $visitor->visitPersonalCustomer($this);
    }
}

interface Visitor {
    public function visitEnerpriseCustomer( EnterpriseCustomer $ec );
    public function visitPersonalCustomer( PersonalCustomer $pc );
}

/**
 *具体的访问者
 *对服务提出请求
 */
class ServiceRequestVisitor implements Visitor {
    /**
     *访问企业客户
     */
    public function visitEnerpriseCustomer( EnterpriseCustomer $ec ) {
        echo $ec->name."企业提出服务请求。\n";
    }

    /**
     *访问个人用户
     *@param $pc PersonalCustomer
     */
    public function visitPersonalCustomer( PersonalCustomer $pc ) {
        echo '客户'.$pc->name."提出请求。\n"; 
    }
}

/**
 *对象结构
 *存储要访问的对象
 */
class ObjectStructure {
    private $obj = array(); 
    public function addElement( $ele ) {
        array_push($this->obj, $ele); 
    }

    /**
     *     *处理请求
     *         *@param $visitor Visitor
     *             */
    public function handleRequest( Visitor $visitor ) {
        //遍历对象结构中的元素，接受访问
        foreach( $this->obj as $ele ) {
            $ele->accept( $visitor );
        } 
    }
}

/*测试*/
header('Content-Type: text/html; charset=utf-8');
//对象结构
$os = new ObjectStructure();

//添加元素
$ele1 = new EnterpriseCustomer();
$ele1->name = 'ABC集团';
$os->addElement( $ele1 );

$ele2 = new EnterpriseCustomer();
$ele2->name = 'DEF集团';
$os->addElement( $ele2 );

$ele3 = new PersonalCustomer();
$ele3->name = '张三';
$os->addElement( $ele3 );

//客户提出服务请求
$serviceVisitor = new ServiceRequestVisitor();
$os->handleRequest( $serviceVisitor );
