/**
 * Created by Administrator on 2017/8/7.
 */
$(function () {
	var uploadData = {

		//文件上传地址
		host: '/upload',

		//swf文件地址
		swf: 'build/webuploader/Uploader.swf',

		//允许的文件类型
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png,image/bmp'
		}
	}

	var uploadObject = {
		/**
		 * 初始化webUploader，对应的参数为： （上传按钮ID，预览区域ID, 按钮文本， input的name值）
		 */
		init: function (pickerID,  preID, innerHTML, valName, cb) {
			this.pickerID = pickerID
			this.innerHTML = innerHTML
			this.valName = valName
			var uploader = this.create();
			this.bindEvent(uploader, preID, cb);
			return uploader;
		},

		/**
		 * 创建webuploader对象
		 */
		create: function () {
			var that = this,
				webUploader = WebUploader.create({
				auto: true,
				pick: {
					id: '#' + that.pickerID,
					multiple: false,// 只上传一个
					innerHTML: that.innerHTML
				},
				//文件上传的name
				fileVal: that.valName,
				accept: uploadData.accept,
				swf: uploadData.swf,
				disableGlobalDnd: true,
				duplicate: true,
				server: uploadData.host,

				//预览图片大小
				thumb: {
					width: 1920,
					height: 1080,
					allowMagnify: true,
					crop: true
				},
			});

			return webUploader;
		},

		/**
		 * 绑定事件
		 */
		bindEvent: function (bindedObj, preID, cb) {
			bindedObj.on('fileQueued', function (file) {
				var $preList = $("#" + preID)
				console.log($preList)
				$preList.html('')
				var $li = $(
					'<div id="' + file.id + '" class="ad-content-img-item">' +
					'<img>' +
					'<div class="ad-content-img-item-info">' + file.name + '</div>' +
					'</div>'
				),
				$img = $li.find('img');

				$preList.html($li);

				// 生成缩略图
				bindedObj.makeThumb(file, function (error, src) {
					if (error) {
						$img.replaceWith('<span>不能预览</span>');
						return;
					}
					$img.attr('src', src);
				});
			});

			// 文件上传过程中创建进度条实时显示。
			bindedObj.on('uploadProgress', function (file, percentage) {
			});

			// 文件上传成功，给item添加成功class, 用样式标记上传成功。
			bindedObj.on('uploadSuccess', function (file, response) {
				$('#' + file.id).addClass('upload-state-done');
				typeof cb === 'function' && cb(response.baseurl)
			});


			// 文件上传失败，显示上传出错。
			bindedObj.on('uploadError', function (file) {
				var $li = $('#' + file.id),
					$error = $li.find('div.error');

				// 避免重复创建
				if (!$error.length) {
					$error = $('<div class="error"></div>').appendTo($li);
				}

				$error.text('上传失败');
			});

			// 完成上传完了，成功或者失败，先删除进度条。
			bindedObj.on('uploadComplete', function (file) {
				$('#' + file.id).find('.progress').remove();
			});

			// 文件上传过程中创建进度条实时显示。
			bindedObj.on('uploadProgress', function (file, percentage) {
				var $li = $('#' + file.id),
					$percent = $li.find('.progress .progress-bar');

				// 避免重复创建
				if (!$percent.length) {
					$percent = $('<div class="progress progress-striped active">' +
						'<div class="progress-bar" role="progressbar" style="width: 0%">' +
						'</div>' +
						'</div>').appendTo($li).find('.progress-bar');
				}

				$li.find('p.state').text('上传中');

				$percent.css('width', percentage * 100 + '%');
			});

		}
}

	var uploadFunc = {
		init: function () {
			this.adCover()
			this.adImg()
		},

		//广告封面上传
		adCover: function () {
			uploadObject.init('adCoverPicker', 'adImgCoverPre', '选择封面图片', 'image', function (baseurl) {
                $('#imgurl').val(baseurl)
            })
		},

		//广告跳转图片上传
		adImg: function () {
			uploadObject.init('adPicker', 'adImgPre', '选择跳转图片', 'image', function (baseurl) {
				$('#imglink').val(baseurl)
            })
		}
	}

	uploadFunc.init()
})