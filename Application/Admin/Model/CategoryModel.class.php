<?php 
namespace Admin\Model;
use Think\Model;

/**
* 商品栏目模型
*/
class CategoryModel extends Model 
{
	// 验证栏目名称不能为空
	protected $_validate = array(
		array('cat_name', 'require', '栏目名称不能为空'),
	);

	//商品栏目无限极分类
	public function getTree()
	{
		
		$cate = $this->select();

		return $this->_getTree($cate);

	}
	// 递归实现
	public function _getTree($data, $parent_id = 0, $level=0)
	{
		// 1. 定义一个static变量
		static $list = array();
		// 2.遍历所有数据
		foreach ($data as $k => $v) {
			// 3.判断
			if($v['pid'] == $parent_id){

				$v['level'] = $level;
				$list[] = $v;

				$this->_getTree($data, $v['id'], $level+1);
			}

		}
		return $list;
	}

	/**
	 * 获取导航栏信息
	 * @return array 对应导航信息（默认是pid为0的数据）
	 */
	public function getNav()
	{
		$data = $this->where('pid = 0')->select();
		return $data;
	}


	/**
	 * 根据当前栏目ID获取其子栏目ID
	 * @param  integer $cateId 栏目ID
	 * @return array         当前栏目的子栏目
	 */
	public function getChild($cateId)
	{
		// 获取子栏目
		$data = $this->select();

		return $this->_getChild($data, $cateId);


	}

	/**
	 * 递归实现获取子栏目
	 * @param  array $data   分类数据
	 * @param  integer $cateId 当前栏目ID
	 * @return array         一维 当前栏目的子栏目ID
	 */
	public function _getChild($data, $cateId)
	{
		static $list = array();

		foreach ($data as $k => $v) {
			
			if($v['pid'] == $cateId){
				$list[] = $v['id'];
				$this->_getChild($data, $v['id']);
			}
		}

		return $list;
	}


	/****************面包屑导航*****************/
	/**
	 * 面包屑导航
	 * @param  integer $cateId 栏目ID
	 * @return array $data 当前栏目的父栏目        
	 *
	 **/
	public function getFamilys($cateId)
	{
		$data = $this->select();
		return $this->_getFamilys($data, $cateId);
	}

	/**
	 * 递归实现面包屑导航
	 * @param  array $data  栏目信息
	 * @param  integer $catId 当前栏目ID
	 * @return array        当前栏目父栏目信息
	 */
	public function _getFamilys($data, $cateId)
	{
		static $list = array();

		foreach ($data as $k => $v) {
			
			if($v['id'] == $cateId){

				$list[] = $v;
				$this->_getFamilys($data, $v['pid']);
			}
		}

		return $list;
	}

	/**
	 * 获取首页分类信息
	 * @return [type] [description]
	 */
	public function getCate()
	{
		$data = $this->select();
		return $this->_getCate($data);

	}
	/**
	 * 分类的子方法
	 * @param  [array]  $data  [需要分类的数据]
	 * @param  integer $pid   [父级ID]
	 * @param  integer $level [分层的级别]
	 * @return [array]         [无限极分类后的数据]
	 */
	public function _getCate($data, $pid = 0, $level = 0)
	{
		static $list = array();
		foreach ($data as $k => $v) {
			if($v['pid'] == $pid){
				$v['level'] = $level;
				$list[] = $v;
				$this->_getCate($data, $v['id'], $level+1);
			}
		}
		return $list;
		
	}



	public function getIds($cateId)
	{
		$data = $this->select();
		return $this->_getIds($data, $cateId);
	}

	public function _getIds($data, $cateId)
	{
		static $list = array();

		foreach ($data as $k => $v) {
			if($v['pid'] == $cateId){
				
				$list[] = $v['id'];
				$this->_getIds($data, $v['id']);
				
			}
		}

		return $list;
	}

	/**
	 * 面包屑导航信息
	 * @param integer $catId [对应分类ID]
	 */
	public function getFamily($catId)
	{
		$data = $this->select();
		return $this->_getFamily($data, $catId);


	}
	/**
	 * 根据分类ID获取其父分类
	 * @param  [array] $data  [分类全部信息]
	 * @param  [integer] $catId [当前分类ID]
	 * @return [array]        [已经获取到当前分类号的父分类信息]
	 */
	public function _getFamily($data, $catId)
	{
		static $list = array();

		foreach ($data as $k => $v) {
			if($v['id'] == $catId){
				$list[] = $v;
				$this->_getFamily($data, $v['pid']); // 为什么要传pid
				// 现在是根据自己认祖归宗 根据当前id 去找 父id
			}
		}

		return $list;
	}
}

 ?>