/**
 * Created by Administrator on 2017/8/10.
 */
$(function () {
	var uploadData = {

		//文件上传地址
		host: '/upload',

		//swf文件地址
		swf: 'build/webuploader/Uploader.swf',

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
					// accept: uploadData.accept,
					swf: uploadData.swf,
					disableGlobalDnd: true,
					duplicate: true,
					server: uploadData.host,

				});

			return webUploader;
		},

		/**
		 * 绑定事件
		 */
		bindEvent: function (bindedObj, preID, cb) {
			bindedObj.on('fileQueued', function (file) {
				var $preList = $("#" + preID)

				$preList.html('')
				$preList.append( '<div id="' + file.id + '" class="item">' +
					'<h4 class="info">' + file.name + '</h4>' +
					'<p class="state">等待上传...</p>' +
					'</div>' )
			});

			// 文件上传成功，给item添加成功class, 用样式标记上传成功。
			bindedObj.on('uploadSuccess', function (file, response) {
				$('#' + file.id).addClass('upload-state-done');
                $('#' + file.id).find('.state').html('上传成功！')
				typeof cb === 'function' && cb(response)
			});


			// 文件上传失败，显示上传出错。
			bindedObj.on('uploadError', function (file) {
				$( '#'+file.id ).find('p.state').text('上传出错')
			});

		}
	}

	var uploadFunc = {
		init: function () {
			this.fileOne()
			this.fileTwo()
			this.fileThree()
		},


		fileOne: function () {
			uploadObject.init('file_1', 'file_1_list', '选择文件', 'image', function (res) {
				$('input[name=sslcert]').val(res.baseurl)
            })
		},

		fileTwo: function () {
			uploadObject.init('file_2', 'file_2_list', '选择文件', 'image', function (res) {
                $('input[name=sslkey]').val(res.baseurl)
            })
		},

		fileThree: function () {
			uploadObject.init('file_3', 'file_3_list', '选择文件', 'image', function (res) {
                $('input[name=cainfo]').val(res.baseurl)
            })
		}
	}

	uploadFunc.init()
})