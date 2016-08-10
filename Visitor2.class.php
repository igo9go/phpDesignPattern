<?php
/**
 * PHP设计模式 之 访问者模式
 */

/**
 * 元素结构层次
 */

/**
 * 元素抽象类
 */
abstract class Browser {
    public $name;

    /**
     * 接受访问者的访问
     * @param Object $visitor
     */
    public abstract function accept($visitor);
}

/**
 * 具体元素类实现
 * 浏览设备：电脑
 */
class PCBrowser extends Browser {
    /**
     * 实现抽象类的抽象方法
     * @see Browser::accept()
     */
    public function accept($visitor){
        $visitor->visitPCBrowser($this);
    }
}
/**
 * 具体元素类实现
 * 浏览设备：手机
 */
class MBBrowser extends Browser {
    /**
     * 实现抽象类的抽象方法
     * @see Browser::accept()
     */
    public function accept($visitor){
        $visitor->visitMBBrowser($this);
    }
}


/**
 * 访问者抽象接口
 */
interface IVisitor {
    /**
     * 电脑设备访问者类型访问，作用于具体元素的操作
     * @param PCBrowser $pc
     */
    public function visitPCBrowser($pc);

    /**
     * 手机设备访问者类型访问，作用于具体元素的操作
     * @param MBBrowser $mb
     */
    public function visitMBBrowser($mb);
}

/**
 * 具体访问者类实现
 * 设备来源分析
 */
class EquipmentAnalyze implements IVisitor {
    /**
     * 电脑设备来源
     * @see IVisitor::visitPCBrowser()
     */
    public function visitPCBrowser($pc){
        //do ...
        echo '电脑访问,浏览器是'.$pc->name."\n";
    }

    /**
     * 手机设备来源
     * @see IVisitor::visitMBBrowser()
     */
    public function visitMBBrowser($mb){
        //do ...
        echo '手机访问,浏览器是'.$mb->name."\n";
    }

}

/**
 * 结构对象
 * 作用：管理访问者,提供访问元素接口
 */
class ObjectStructure {

    private $visitor = array();

    /**
     * 新增访问者
     * @param Object $vis
     */
    public function addVisitor($vis){
        if(is_object($vis)){
            $this->visitor[spl_object_hash($vis)] = $vis;
        }
    }

    /**
     * 删除访问者
     * @param Object $vis
     */
    public function delVisitor($vis){
        if(is_object($vis)){
            unset($this->visitor[spl_object_hash($vis)]);
        }
    }
    /**
     * 访问者访问元素接口
     * @param Object $vistor
     */
    public function handler($vistor){
        foreach ($this->visitor as $obj){
            $obj->accept($vistor);
        }
    }

}

///创建访问者管理对象
$os = new ObjectStructure();

///创建具体元素
$pc = new PCBrowser();
$pc->name = '360';
$os->addVisitor($pc);

///创建具体元素
$mb = new MBBrowser();
$mb->name = 'uc';
$os->addVisitor($mb);

///创建访问者
$vistor = new EquipmentAnalyze();

///作用于$os->visitor集合的各个元素
$os->handler($vistor);
