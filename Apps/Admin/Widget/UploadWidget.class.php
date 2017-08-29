<?php
/**
 * web upload 上传部件
 * 调用上传部件前需要在页面中引入webupload相关的js与样式
 * User: Administrator
 * Date: 2017/7/26
 * Time: 9:14
 * ==========================================
 * 前台调用
 * {:W('Upload/upload_one',array(
        array(
        'serverUrl' => $serverUrl,
        'uploadMethod' => 'upload_gift_banner',
        'thumbnailWidth' => 400,
        'thumbnailHeight' => 150,
        'imgUrl' => 'banner_url',
        'imagePath' => $bannerPath,
        'imageWidth' => 1920,
        'imageHeight' => 500
        ),
    ))}
 * ==========================================
 */

namespace Admin\Widget;
use Think\Controller;

class UploadWidget extends Controller
{

    protected $imageContainer;      //展示缩略图的容器id
    protected $imageChoseBtn;       //选择图片的按钮id
    protected $imageUploadBtn;      //图片上传按钮的id

    //图片参数
    protected $imageSize;           //允许上传的图片大小
    protected $thumbnailWidth;      //缩略图宽度
    protected $thumbnailHeight;     //缩略图高度
    protected $display;             //展示方式 1横版，2竖版
    protected $chunked;             //是否分片上传，true是，false否，默认否
    protected $chunkSize;           //分片大小
    protected $imageWidth;          //允许图片宽度
    protected $imageHeight;         //允许图片高度

    protected $serverUrl;           //上传图片服务器地址
    protected $uploadMethod;        //服务器具体执行上传的方法

    //js变量的名称
    protected $imgUrl;              //js上传图片成功后保存图片的变量名
    protected $imgUrlDelete;        //js记录删除图片的变量名
    protected $isSuccess;           //js图片上传是否成功的变量名
    protected $noUpload;            //js否有上传图片文件的变量名
    protected $isUpdate;            //js是否有更新上传图片文件的变量名
    protected $imageNum;            //js已经上传图片数量的变量名

    protected $imagePath;           //已经上传的图片地址

    public function __construct()
    {
        parent::__construct();

        $this->imageContainer = 'imageContainer';
        $this->imageChoseBtn = 'imageChoseBtn';
        $this->imageUploadBtn = 'imageUploadBtn';

        $this->imageSize = 2*1024*1024;
        $this->thumbnailWidth = 100;
        $this->thumbnailHeight = 100;
        $this->display = 1;
        $this->chunked = 'false';
        $this->imageHeight = 400;
        $this->imageWidth = 1170;

        $this->chunkSize = 2*1024*1024;

        $this->imgUrl = 'img_url';
        $this->imgUrlDelete = 'img_url_del';
        $this->isSuccess = 'is_success';
        $this->noUpload = 'no_upload';
        $this->isUpdate = 'is_update';
        $this->imageNum = 'img_num';
    }

    /**
     * 上传单张图片
     * @author xy
     * @since 2017/07/26
     * @param array $param
     */
    public function upload_one(array $param = array()){
        if(empty($param)||empty($param['serverUrl'])||empty($param['uploadMethod'])){
            $this->error('请指明上传的服务端配置');
        }
        //上传图片服务端的地址
        if(!empty($param['serverUrl'])){
            $this->serverUrl = $param['serverUrl'];
        }
        //服务端上传图片的方法
        if(!empty($param['uploadMethod'])){
            $this->uploadMethod = $param['uploadMethod'];
        }
        //展示缩略图的容器id
        if(!empty($param['imageContainer'])){
            $this->imageContainer = $param['imageContainer'];
        }
        //选择图片的按钮id
        if(!empty($param['imageChoseBtn'])){
            $this->imageChoseBtn = $param['imageChoseBtn'];
        }
        //图片上传按钮的id
        if(!empty($param['imageUploadBtn'])){
            $this->imageUploadBtn = $param['imageUploadBtn'];
        }
        //允许上传的图片的大小kb
        if(!empty($param['imageSize'])){
            $this->uploadMethod = $param['imageSize'];
        }

        //允许上传的图片的缩略图宽度
        if(!empty($param['thumbnailWidth'])){
            $this->thumbnailWidth = $param['thumbnailWidth'];
        }
        //允许上传的图片的缩略图高度
        if(!empty($param['thumbnailHeight'])){
            $this->thumbnailHeight = $param['thumbnailHeight'];
        }
        //允许上传的图片宽度
        if(!empty($param['imageWidth'])){
            $this->imageWidth = $param['imageWidth'];
        }
        //允许上传的图片高度
        if(!empty($param['imageHeight'])){
            $this->imageHeight = $param['imageHeight'];
        }


        //已经删除上传的图片的路径
        if(!empty($param['imagePath'])){
            $this->imagePath = $param['imagePath'];
        }
        //是否开启分片
        if(!empty($param['chunked'])){
            $this->chunked = $param['chunked'];
        }

        //分片大小
        if(!empty($param['chunkSize'])){
            $this->chunkSize = $param['chunkSize'];
        }

        if(!empty($param['imgUrl'])){
            $this->imgUrl = $param['imgUrl'];
        }
        if(!empty($param['isSuccess'])){
            $this->isSuccess = $param['isSuccess'];
        }
        if(!empty($param['noUpload'])){
            $this->noUpload = $param['noUpload'];
        }
        if(!empty($param['isUpdate'])){
            $this->isUpdate = $param['isUpdate'];
        }

        $uploadOneHtml = $this->uploadSingleHtml();
        $uploadOneJs = $this->uploadSingleJs();
        $this->assign('uploadOneHtml',$uploadOneHtml);
        $this->assign('uploadOneJs',$uploadOneJs);


        $this->display('Widget:Upload:upload_one');
    }

    /**
     * 生成上传单张图片的html
     * @author xy
     * @since 2017/07/26 10:54
     * @return string
     */
    protected function uploadSingleHtml(){
        $imageSize = $this->imageSize/(1024*1024);
        $imgStr = '';
        if(!empty($this->imagePath)){
            $imagePath = format_url($this->imagePath);
            $imgStr = '<img style="width: 128px;height: 128px;" src="'.$imagePath.'" />';
        }
        $htmlStr = <<<html
<div class="uploader-box">
    <!--用来展示图片-->
    <div id="{$this->imageContainer}" class="uploader-list">
        {$imgStr}
    </div>
    <div class="icon-btn-wrap mt-10">
        <div id="{$this->imageChoseBtn}">选择图片</div>
        <a id="{$this->imageUploadBtn}" class="btn btn-default">开始上传</a>
        <span style="color: #17983b;margin-top: 5px;" class="r">(最佳显示效果{$this->imageWidth}*{$this->imageHeight},图片大小<{$imageSize}M</span>
    </div>
</div>
html;
        return $htmlStr;
    }

    /**
     * 上传单张图片js
     * @author xy
     * @since 2017/08/07 16:59
     * @return string
     */
    public function uploadSingleJs(){
        $publicPath = __ROOT__.'/Public';

        $jsStr = <<<js
            
            var {$this->imgUrl} = [];
            var {$this->isSuccess} = false;
            var {$this->isUpdate} = false;
            var {$this->noUpload} = true;
            var imageThumbnailWidth = {$this->thumbnailWidth};
            var imageThumbnailHeight = {$this->thumbnailHeight};
            uploader_1 = WebUploader.create({
                auto: false,

                swf: '{$publicPath}/H-ui/lib/webuploader/0.1.5/Uploader.swf',

                server: "{$this->serverUrl}",
                formData: {fun: '{$this->uploadMethod}'},

                chunked: "{$this->chunked}",
                chunkSize: "{$this->chunkSize}",
                threads: 1,

                pick: {
                    multiple: false,
                    id: '#{$this->imageChoseBtn}'
                },

                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,image/bmp'
                },
                fileSizeLimit: '{$this->imageSize}',
            });

            $('#{$this->imageUploadBtn}').click(function () {
                uploader_1.upload();
            });
            uploader_1.on('error', function (error) {
                if (error == 'Q_EXCEED_SIZE_LIMIT') {
                    layer.alert('文件大小超过{$this->imageSize}k，不允许上传', {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4 
                    });
                }
                if (error == 'Q_TYPE_DENIED') {
                    layer.alert('文件类型错误', {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4
                    });
                }
            });

            uploader_1.on('beforeFileQueued', function (file) {
                uploader_1.reset();
                {$this->noUpload} = false;
                {$this->isUpdate} = true;
            });

            uploader_1.on('fileQueued', function (file) {
                file_id = file.id;
                var li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                    '</div>'
                ),
                img = li.find('img');

                imageList = $("#{$this->imageContainer}");
                imageList.html(li);

                uploader_1.makeThumb(file, function (error, src) {
                    if (error) {
                        img.replaceWith('<span>不能预览</span>');
                        return;
                    }
                    img.attr('src', src);
                }, imageThumbnailWidth, imageThumbnailHeight);
            });

            uploader_1.on('uploadProgress', function (file, percentage) {
                var li = $('#' + file.id),
                percent = li.find('.progress span');


                if (!percent.length) {
                percent = $('<p class="progress"><span></span></p>')
                    .appendTo(li)
                    .find('span');
                }
                percent.css('width', percentage * 100 + '%');
            });
            uploader_1.on("uploadAccept", function (file, data) {
                if (data.error) { 
                    layer.alert(data.info, {
                        skin: 'layui-layer-lan',
                        closeBtn: 0,
                        anim: 4 
                    });
                    return false;
                }
                else {
                    {$this->isSuccess} = true;
                    {$this->imgUrl}[0] = data.url;
                }
            });

            uploader_1.on('uploadSuccess', function (file) {
                {$this->isSuccess} = true;
                $('#' + file.id).addClass('upload-state-done');
            });

            uploader_1.on('uploadError', function (file) {
                {$this->isSuccess} = false;
                var li = $('#' + file.id),
                    error = li.find('div.error');

                if (error.length) {
                    error = $('<div class="error"></div>').appendTo(li);
                }

                error.text('上传失败');
            });

            uploader_1.on('uploadComplete', function (file) {
                $('#' + file.id).find('.progress').remove();
            });
js;
        return $jsStr;
    }

    /**
     * 上传多张图片的widget
     * @param array $param 配置参数
     * @author xy
     * @since 2017/08/08 15:27
     */
    public function upload_multi(array $param = array()){
        if(empty($param)||empty($param['serverUrl'])||empty($param['uploadMethod'])){
            $this->error('请指明上传的服务端配置');
        }
        //上传图片服务端的地址
        if(!empty($param['serverUrl'])){
            $this->serverUrl = $param['serverUrl'];
        }
        //服务端上传图片的方法
        if(!empty($param['uploadMethod'])){
            $this->uploadMethod = $param['uploadMethod'];
        }
        //展示缩略图的容器id
        if(!empty($param['imageContainer'])){
            $this->imageContainer = $param['imageContainer'];
        }
        //选择图片的按钮id
        if(!empty($param['imageChoseBtn'])){
            $this->imageChoseBtn = $param['imageChoseBtn'];
        }
        //图片上传按钮的id
        if(!empty($param['imageUploadBtn'])){
            $this->imageUploadBtn = $param['imageUploadBtn'];
        }
        //允许上传的图片的大小kb
        if(!empty($param['imageSize'])){
            $this->uploadMethod = $param['imageSize'];
        }
        //允许上传的图片的缩略图宽度
        if(!empty($param['thumbnailWidth'])){
            $this->thumbnailWidth = $param['thumbnailWidth'];
        }
        //允许上传的图片的缩略图高度
        if(!empty($param['thumbnailHeight'])){
            $this->thumbnailHeight = $param['thumbnailHeight'];
        }
        //允许上传的图片宽度
        if(!empty($param['imageWidth'])){
            $this->imageWidth = $param['imageWidth'];
        }
        //允许上传的图片高度
        if(!empty($param['imageHeight'])){
            $this->imageHeight = $param['imageHeight'];
        }
        //已经删除上传的图片的路径
        if(!empty($param['imagePath'])){
            $this->imagePath = $param['imagePath'];
        }
        //设置展示方式
        if(!empty($param['display'])){
            $this->display = $param['display'];
        }
        //是否开启分片
        if(!empty($param['chunked'])){
            $this->chunked = $param['chunked'];
        }
        //分片大小
        if(!empty($param['chunkSize'])){
            $this->chunkSize = $param['chunkSize'];
        }

        if(!empty($param['imgUrl'])){
            $this->imgUrl = $param['imgUrl'];
        }
        if(!empty($param['imgUrlDelete'])){
            $this->imgUrlDelete = $param['imgUrlDelete'];
        }
        if(!empty($param['isSuccess'])){
            $this->isSuccess = $param['isSuccess'];
        }
        if(!empty($param['noUpload'])){
            $this->noUpload = $param['noUpload'];
        }
        if(!empty($param['isUpdate'])){
            $this->isUpdate = $param['isUpdate'];
        }
        if(!empty($param['imageNum'])){
            $this->imageNum = $param['imageNum'];
        }

        $uploadMultiHtml = $this->uploadMultiHtml();
        $uploadMultiJs = $this->uploadMultiJs();
        $this->assign('uploadMultiHtml',$uploadMultiHtml);
        $this->assign('uploadMultiJs',$uploadMultiJs);


        $this->display('Widget:Upload:upload_multi');

    }

    /**
     * 上传多张图片html
     * @author xy
     * @since 2017/08/08 14:19
     */
    protected function uploadMultiHtml(){
        if($this->display == 1){
            $this->thumbnailWidth = 115;
            $this->thumbnailHeight = 65;
        }
        if($this->display == 2){
            $this->thumbnailWidth = 65;
            $this->thumbnailHeight = 115;
        }
        //计算图片大小
        $imageSize = $this->imageSize/(1024*1024);
        $imageHtml = '';
        if(!empty($this->imagePath)){
            $imagePathArray = explode(',',$this->imagePath);
            foreach ($imagePathArray as $imagePath){
                $imagePath = format_url($this->imagePath);
                $imageHtml .=
                    '<div class="sub-load-box">
                        <div class="upload-state-done"><img alt="" src="'.$imagePath.'"></div>
                        <div class="load-mask"></div>
                        <img alt="" data-url="" src="'.__ROOT__.'/Public'.'/imgclose.png" class="close-img">
                    </div>';
            }
        }
        $html = <<<html
<div class="uploader-box">
    <!--用来存放item-->
    <div class="game-jt">
        <div>
            <div id="{$this->imageContainer}" class="uploader-list" >
                {$imageHtml}
            </div>
            <div class="loadpic-btn">
                <div id="{$this->imageChoseBtn}">选择图片</div>
                <div class="upload-btn-div">
                    <a id="{$this->imageUploadBtn}" class="begin-upload-btn" href="javascript:void(0);">开始上传</a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <span style="color: #17983b;margin-top: 5px;" class="r">(最多可上传10张图，显示效果{$this->imageWidth}*{$this->imageHeight},同比例亦可，单张图片大小<{$imageSize}M)</span>
</div>
html;
        return $html;
    }

    /**
     * 上传多张图片的js
     * @author xy
     * @since 2017/08/08 14:58
     */
    protected function uploadMultiJs(){
        $publicPath = __ROOT__.'/Public';
        //计算图片大小
        $imageSize = $this->imageSize/(1024*1024);
        $imageNum = $this->imageNum;
        if(!empty($this->imagePath)){
            $imagePathArray = explode(',',$this->imagePath);
            $imageNum = count($imagePathArray);
        }
        if($this->display == 1){
            $this->thumbnailWidth = 115;
            $this->thumbnailHeight = 65;
        }
        if($this->display == 2){
            $this->thumbnailWidth = 65;
            $this->thumbnailHeight = 115;
        }
        $js = <<<js
        
    var {$this->imgUrl} = [];             // 聚合图片数组
    var {$this->isSuccess}  = true;   // 聚合图片上传是否成功
    var {$this->imgUrlDelete} = [];         // 替换删除的图片
    var {$this->isUpdate}  = false;    // 是否有更新上传聚合图片
    var {$this->noUpload}  = true;    // 是否有上传聚合图片
    var {$this->imageNum} = '{$imageNum}'; //记录当前有几张截图
    //缩略图默认宽高
    var thumbnailWidth = '{$this->thumbnailWidth}';
    var thumbnailHeight = '{$this->thumbnailHeight}';
    
    $('.sub-load-box').click(function () {
        if($(this).hasClass('on')) {
            $(this).removeClass('on').siblings().removeClass('on');
        }
        else {
            $(this).addClass('on').siblings().removeClass('on');
        }
    });
    $('.sub-load-box').find('.close-img').click(function (e) {
        {$this->imgUrlDelete}.push($(this).data('url'));
        $(this).parent().remove();
    });

    //根据展示方式设置缩略图宽高

    // 初始化Web Uploader
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: false,

        // swf文件路径
        swf: '{$publicPath}/H-ui/lib/webuploader/0.1.5/Uploader.swf',

        // 文件接收服务端。
        server: "{$this->serverUrl}",
        formData: {fun : '{$this->uploadMethod}'},
        
         chunked: '{$this->chunked}',//开启分片上传
         chunkSize : '{$this->chunkSize}',  //每个分片的大小
         threads: 1,//上传并发数

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#{$this->imageChoseBtn}',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,image/bmp'
        },
        fileSizeLimit: '{$this->imageSize}',
    });
    uploader.on('error', function (error) {
        if(error == 'Q_EXCEED_SIZE_LIMIT') {
            layer.alert('文件大小超过{$imageSize}M，不允许上传', {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
        }
        if(error == 'Q_TYPE_DENIED') {
            layer.alert('文件类型错误', {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
        }
    });
    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        var li = $(
                '<div class="sub-load-box">' +
                '<div id="' + file.id + '"><img alt="" src="{$publicPath}/imgbcg.png" style="height:{$this->thumbnailHeight};width:{$this->thumbnailWidth};"></div>' +
                '<div class="load-mask">' + file.name + '</div>' +
                '<img alt="" src="{$publicPath}/imgclose.png" class="close-img">' +
                '</div>'
            ),
            img = li.find('div:first-child img');
        li.click(function () {
            if($(this).hasClass('on')) {
                $(this).removeClass('on').siblings().removeClass('on');
            }
            else {
                $(this).addClass('on').siblings().removeClass('on');
            }
        });
        //点击关闭图标删除图片时 记录的截图数量-1
        li.find('.close-img').click(function (e) {
            uploader.removeFile(file, true);
            li.remove();
            {$this->imageNum} = parseInt({$this->imageNum}) - 1;
        });

        var fileList_pic;
        fileList_pic = $("#{$this->imageContainer}");
        // 替换
        if($('#{$this->imageContainer} .sub-load-box').length > 0) {
            $('#{$this->imageContainer} .sub-load-box').each(function(z,that){
                if($(this).hasClass('on')) {
                    // 判断是否已上传成功
                    if($(this).find('div:first').hasClass('upload-state-done')) {
                        if($(this).find('div:first').attr('id')) {
                            // 替换删除文件如果是添加则去除数组对应的值
                            {$this->imgUrlDelete}.push({$this->imgUrl}[$(this).find('div:first').attr('id')]);
                            // 调用删除图片
                            delete  {$this->imgUrl}[$(this).find('div:first').attr('id')];
                        }
                        if($(this).find('.close-img').data('url')) {
                            {$this->imgUrlDelete}.push($(this).find('.close-img').data('url'));
                        }
                    }
                    if($(this).find('div:first').attr('id')) {
                        uploader.removeFile($(this).find('div:first').attr('id'), true);
                    }
                    $(this).before(li);
                    fileList_pic = $('#{$this->imageContainer}').eq($(this).index()+1);
                    $(this).remove();
                }
            });
        }

        fileList_pic.append( li );
        {$this->imageNum} = parseInt({$this->imageNum}) + 1;

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                img.replaceWith('<span>不能预览</span>');
                return;
            }

            img.attr( 'src', src );
        }, thumbnailWidth, thumbnailHeight );

    });
    // 点击上传
    $('#{$this->imageUploadBtn}').click(function () {
        uploader.upload();
    });
    // 当文件被加入队列之前触发
    uploader.on( 'beforeFileQueued', function( file ) {
        if({$this->imageNum} > 9){
            layer.alert('最多只能添加10张', {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
            return false;
        }
        {$this->noUpload} = true;
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var li = $( '#'+file.id ),
            percent = li.find('.progress span');

        // 避免重复创建
        if ( !percent.length ) {
            percent = $('<p class="progress"><span></span></p>')
                .appendTo( li )
                .find('span');
        }

        percent.css( 'width', percentage * 100 + '%' );
    });


    uploader.on("uploadAccept", function( file, data){
        if (data.error) { // 通过return false来告诉组件，此文件上传有错。 return false;
            layer.alert(data.info, {
                skin: 'layui-layer-lan',
                closeBtn: 0,
                anim: 4 //动画类型
            });
            return false;
        }
        else{
            {$this->imgUrl}[file.file.id] = data.url;
            {$this->isSuccess} = true;
        }
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ) {
        $( '#'+file.id ).addClass('upload-state-done');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        var li = $( '#'+file.id ),
            error = li.find('div.error');

        // 避免重复创建
        if ( !error.length ) {
            error = $('<div class="error"></div>').appendTo( li );
        }

        error.text('上传失败');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').remove();
    });
js;
        return $js;
    }

}