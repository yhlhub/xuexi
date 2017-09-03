<?php
namespace app\admin\controller;

use think\Request;
use think\Db;
class Article extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询已发布新闻
        $new = $this->loadArticleList(input());
        $this->assign('new',$new);
        $this->assign('input',input());
        // 显示资源列表
        $this->assign('title','咨询列表');
        return $this->fetch('article_list');
    }

    protected function loadArticleList($input,$pageSize=10,$state = 'publish')
    {
        $m = new \app\admin\model\Article();
//        $m = Db::name('article')->alias('a')->join('column b','a.cid = b.html_value')->where('state','=',$state);
        $m->where('state','=',$state);
        if($input) {
            if(isset($input['start']) && $input['start']) {
                $m->where('update_at','>=',$input['start']);
            } 
            if(isset($input['end']) && $input['end']) {
                $end = $input['end']." 23:59:59";
                $m->where('update_at','<=',$end);
            } 
            if (isset($input['title']) && $input['title']) {
                $m->where('title','like','%'.$input['title'].'%');
            }
        }
        return $m->paginate($pageSize);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {  
        //分类
        $col = Db::name('column')
                ->field('name,html_value')
                ->select();
        $this->assign('col',$col);

        $this->assign('title','新增文章 - 资讯管理');
        return $this->fetch('article_add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        //新增新闻
        if(request()->isPost()){
            $data = [
                'title'=>input('articletitle'),
                'short_title'=>input('articletitle2'),
                'cid'=>input('articlecolumn'),
                'keyword'=>input('keywords'),
                'abs'=>input('abstract'),
                'author'=>input('author'),
                'source'=>input('sources'),
                'update_time'=>date('Y-m-d H:i:s'),
                'editor'=>input('content'),
                'state'=>'pbulish',
            ];
            if($_FILES['image']['tmp_name']){
                $file = request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $name = $info->getSaveName();
                $data['img'] = $name;
            }
             if(db('article')->insert($data)){
                return $this->success('添加成功','Article/index');
            }else{
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //显示指定跳转编辑页面新闻
        $edit = Db::name('article')->where('id',$id)->find();
        $this->assign('edit',$edit);
        //分类
        $col = Db::name('column')
                ->field('name,html_value')
                ->select();
        $this->assign('col',$col);
        
        return $this->fetch('article_edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
        if(request()->isPost()){
            $data = [
                'title'=>input('articletitle'),
                'short_title'=>input('articletitle2'),
                'cid'=>input('articlecolumn'),
                'keyword'=>input('keywords'),
                'abs'=>input('abstract'),
                'author'=>input('author'),
                'source'=>input('sources'),
                'update_time'=>date('Y-m-d H:i:s'),
                'editor'=>input('content'),
                'state'=>'pbulish',
            ];
            if($_FILES['image']['tmp_name']){
                $file = request()->file('image');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $name = $info->getSaveName();
                $data['img'] = $name;
            }
             if(db('article')->where('id',$id)->update($data)){
                return $this->success('修改成功','Article/index');
            }else{
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        Db::name('Article')->where('id',$id)->delete();
        return true;
        // if($del) return $this->success('删除成功','Article/index');
        // else return $this->error('删除失败');
    }
}
