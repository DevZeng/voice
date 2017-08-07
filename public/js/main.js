/**
 * Created by Administrator on 2017/8/3.
 */

$(function () {

	//所有数据
	var globalData = {
		host: 'https://sennkisystem.cn/api',

		//评论模板
		template_comment: '<div class="single-item-comments-item">' +
		'<div class="single-item-header">' +
		'<div>' +
		'<img src="{{src}}" alt="{{name}}" class="check-item-header-img">' +
		'<span class="single-item-header-name">{{name}}</span>' +
		'</div>' +
		'<span class="delete-comment" data-id="{{id}}">删除评论</span>' +
		'</div>' +
		'<div class="single-item-comments-item-content">' +
		'{{content}}' +
		'</div>' +
		'</div>',

		//评论页数
		comment_page: 1,
		more_comment_flag: false,
	}

	//中间件
	var octopus = {

		init: function () {
			funcCommon.init()
			funcControl.init()
		}
	}

	//通用函数部分
	var funcCommon = {

		init: function () {
			this.appSwitch()
			this.navSwitch()
			this.logout()
		},

		//区小程序切换
		appSwitch: function () {
			var $app_list = $('#app_list'),
				$switch_app = $('#switch_app')
			$app_list.css('bottom', -($app_list.height() + 14) + 'px')

			$switch_app.on('click', function () {
				var state = $app_list.css('display')
				if(state === 'block'){
					$switch_app.css('color','#fff')
					$app_list.hide()
				}else {
					$switch_app.css('color','#1ccca9')
					$app_list.show()
				}
			})
		},

		//导航切换
		navSwitch: function () {
			var $nav_show = $('#nav_show'),
				$nav_hide = $('#nav_hide'),
				$index_nav = $('#index_nav'),
				$index_nav_hide = $('#index_nav_hide')

			$nav_show.on('click',function () {
				$index_nav.show()
				$index_nav_hide.hide()
			})

			$nav_hide.on('click',function () {
				$index_nav_hide.show()
				$index_nav.hide()
			})
		},

		//退出登录
		logout: function () {
			var $logout = $('#logout')

			$logout.on('click', function () {
				if(confirm('确定退出登录吗？')){

				}
			})
		}

	}

	//管理函数
	var funcControl = {
		//初始化
		init: function () {
			this.userBan()
			this.userAll()
			this.userAllBan()
			this.userAllBanCancel()
			this.userCheckPass()
			this.userCheckReject()
			this.userCheckAll()
			this.allCheckPast()
			this.allCheckReject()
			this.deletePast()
			this.infoAll()
			this.deleteAllInfo()
			this.deleteComments()
			this.nextComments()
			this.deleteSingleInfo()
			this.adAll()
			this.deleteAllad()
			this.deleteAd()
		},

		//用户禁言
		userBan: function () {
			var $user_ban = $('.user-ban')
			$user_ban.on('click', function () {
				var $father = $(this).parents('tr'),
					$state_text = $father.find('.status'),
					data = $father.data()

				//1是正常，2是禁言
				if(data.status === 1){
					/**
					* 发起请求
					* */
					/*$.ajax({
						url: globalData.host,
						method: 'POST',
						data: {
							id: data.id
						},
						success: function () {

						}
					})*/
					if(confirm('确定禁言吗？')){
						$state_text.html('禁言中')
						$state_text.addClass('ban')
						$father.data('status', 2)
						$(this).html('取消')
					}
				}else if(data.status === 2){
					if(confirm('确定取消禁言吗？')){
						$state_text.html('正常')
						$state_text.removeClass('ban')
						$father.data('status', 1)
						$(this).html('禁言')
					}
				}
				return false
			})
		},
		
		//用户列表全选
		userAll: function () {
			var $user_all = $('#user_all'),
				$checkbox = $user_all.find("input[type='checkbox']"),
				$user_name = $('.user-name')

			$user_all.on('click', function () {
				var checked = $checkbox.prop('checked')
				if(checked){
					$checkbox.prop('checked', false)
					$user_name.prop('checked', false)
				}else {
					$checkbox.prop('checked', true)
					$user_name.prop('checked', true)
				}
			})
		},

		//禁言函数
		userAllBanFunc: function (target, ban) {
			var data = []
			if(ban){
				for(var i = 0; i < target.length; i++){
					var checked = $(target[i]).prop('checked'),
						$father = $(target[i]).parents('tr'),
						tmp = $father.data()
					if(checked && tmp.status === 0){
						data.push(tmp.id)
					}
				}
			}else {
				for(var i = 0; i < target.length; i++){
					var checked = $(target[i]).prop('checked'),
						$father = $(target[i]).parents('tr'),
						tmp = $father.data()
					if(checked && tmp.status === 1){
						data.push(tmp.id)
					}
				}
			}

			return data
		},

		//全选后禁言
		userAllBan: function () {
			var $user_ban_all = $('#user_ban_all'),
				data = []

			$user_ban_all.on('click', function () {
				var $user_name = $('.user-name')
				data = funcControl.userAllBanFunc($user_name, true)
				if(data.length){
					if (confirm('确定全部禁言吗？')){
						if(data.length){
							/**
							 * 发起请求
							 * */
							// $.ajax({
							// 	 url: globalData.host,
							// 	 method: 'POST',
							// 	 data: data,
							// 	 success: function (res) {
							//		for(var i = 0; i < data.length; i ++){
							//			var $tmp = $('#' + data[i]),
							//				$item = $tmp.find('.status')
                            //			$tmp.data('status', 1)
							// 			$item.html('禁言中')
                            //          $item.addClass('ban')
                            // 			$tmp.find('a').html('取消')
							// 		}
							// 	 }
							//  })
						}
					}
				}
			})
		},

		//全选取消禁言
		userAllBanCancel: function () {
			var $user_ban_all_cancel = $('#user_ban_all_cancel'),
				data = []

			$user_ban_all_cancel.on('click', function () {
				var $user_name = $('.user-name')
				data = funcControl.userAllBanFunc($user_name, false)
				console.log(data)
				if(data.length){
					if (confirm('确定全部取消禁言吗')){

						if(data.length){
							/**
							 * 发起请求
							 * */
							/*$.ajax({
							 url: globalData.host,
							 method: 'POST',
							 data: data,
							 success: function () {
							//		for(var i = 0; i < data.length; i ++){
							//			var $tmp = $('#' + data[i]),
							//				$item = $tmp.find('.status')
							//			$tmp.data('status', 0)
							// 			$item.html('正常')
                            //          $item.removeClass('ban')
                            // 			$tmp.find('a').html('禁言')
							// 		}
							 }
							 })*/
						}
					}
				}
			})
		},

		//单条审核通过
		userCheckPass: function () {
			var $check_pass = $('.check-pass')

			$check_pass.on('click', function () {
				var $father = $(this).parents('.check-item'),
					kind = $father.data()
				/*$.ajax({
					url: globalData.host,
					data: {
						id: kind.id,
						type: kind.type
					},
					success: function () {

					}
				})*/
				$father.remove()
			})
		},

		//单条审核拒绝
		userCheckReject: function () {
			var $check_pass = $('.check-pass')

			$check_pass.on('click', function () {
				var $father = $(this).parents('.check-item'),
					kind = $father.data()
				/*$.ajax({
				 url: globalData.host,
				 data: {
				 id: kind.id
				 type: kind.type
				 },
				 success: function () {

				 }
				 })*/
				$father.remove()
			})
		},

		//获取审核id
		getCheckID: function (target) {
			var data = []

			for (var i = 0; i < target.length; i++){
				data.push($(target[i]).data().id)
			}
			return data
		},

		//全选通过
		allCheckPast: function () {
			var $check_all_past = $('#check_all_past')

			$check_all_past.on('click', function () {
				var kind = $('#check_all').data()
				if (1 === kind.status){
					return false
				}else if (2 === kind.status){
					var $all_item = $('.check-item'),
						data = funcControl.getCheckID($all_item)
					if(data.length){
						if (confirm('确定通过所有信息？')){
							/**
							 * 发送请求
							 * */
							/*$.ajax({
							 url: globalData.host,
							 method: 'POST',
							 data: {
							 id: data,
							 type: kind.type
							 },
							 success: function () {

							 }
							 })*/

							//重载页面
							window.location.reload()
						}
					}
				}
			})
		},

		//全选拒绝
		allCheckReject: function () {
			var $check_all_reject = $('#check_all_reject')

			$check_all_reject.on('click', function () {
				var kind = $('#check_all').data()

				if (1 === kind.status){
					return false
				}else if (2 === kind.status){
					var $all_item = $('.check-item'),
						data = funcControl.getCheckID($all_item)
					if(data.length){
						if ('确定拒绝所有信息？'){
							/**
							 * 发送请求
							 * */
							/*$.ajax({
							 url: globalData.host,
							 method: 'POST',
							 data: {
							 id: data,
							 type: kind.type
							 },
							 success: function () {

							 }
							 })*/
							window.location.reload()
						}
					}
				}
			})
		},

		//审核全选
		userCheckAll: function () {
			var $check_all = $('#check_all')

			$check_all.on('click', function () {
				var $all_item = $('.check-item'),
					status = $(this).data('status')
				//1代表未选中，2代表选中
				if (1 === status){
					$(this).html('取消')
					$all_item.addClass('active')
					status = $(this).data('status', 2)
				}else if (2 === status){
					$(this).html('全选')
					$all_item.removeClass('active')
					status = $(this).data('status', 1)
				}
			})
		},

		//删除已通过信息
		deletePast: function () {
			var del_btn = $('.user-past-del')

			del_btn.on('click', function () {
				if (confirm('确定删除该信息吗')){
					var $father = $(this).parents('table'),
						id = $(this).data('id')

					/**
					 * 发送请求
					 * */
					/*$.ajax({
						url: globalData.host,
						data: {
							id: id
						},
						success: function () {

						}
					})*/
					$father.remove()
					return false
				}
			})
		},

		//全选已通过信息
		infoAll: function () {
			var $info_all_btn = $('#info_all'),
				$checkbox = $info_all_btn.find("input[type='checkbox']"),
				$info_all = $('.past-content')

			$info_all_btn.on('click', function () {
				var check = $checkbox.prop('checked')
				if (check){
					$checkbox.prop('checked', false)
					$info_all.prop('checked', false)
				}else {
					$checkbox.prop('checked', true)
					$info_all.prop('checked', true)
				}
			})
		},
		
		//多选删除已通过信息
		deleteAllInfo: function () {
			var $delete_btn = $('#del_all_past')

			$delete_btn.on('click', function () {
				var $allInfo = $('.past-content'),
					data = []

				for(var i = 0; i < $allInfo.length; i++){
					//保存被选中项的id
					if($($allInfo[i]).prop('checked')){
						data.push($($allInfo[i]).data('id'))
					}
				}

				if(data.length){
					/**
					 * 发送请求
					 * */
					/*if(confirm('确认删除所选信息吗？')){
					 $.ajax({
					 url: globalData.host,
					 method: 'POST',
					 data: data,
					 success: {

					 //重载页面
					 window.location.reload()
					 }
					 })
					 }*/
				}
			})
		},

		//具体页面中，单条信息删除
		deleteSingleInfo: function () {
			var $single_del = $('#single_del')

			$single_del.on('click',function () {
				var $father = $(this).parents('.single-item'),
					id = $(this).data('id')
				if(confirm('确定删除该条信息吗？')){
					/**
					 * 请求
					 * */
					/*$.ajax({
					 url: globalData.host,
					 method: 'POST',
					 data: {
					 id: id
					 },
					 success: function () {

					 }
					 })*/
					$father.remove()
					window.history.back(-1)
				}
			})
		},

		//评论删除
		deleteComments: function () {
			var $delete_comment = $('.delete-comment')

			$delete_comment.off('click')
			$delete_comment.on('click', function () {
				var $father = $(this).parents('.single-item-comments-item'),
					id = $(this).data('id')
				if(confirm('确定删除该评论吗？')){

					/**
					* 请求
					* */
					/*$.ajax({
						url: globalData.host,
						method: 'POST',
						data: {
							id: id
						},
						success: function () {
					        $father.remove()
						}
					})*/
					$father.remove()
				}
			})
		},

		//评论模板数据修改
		setCommentData: function (data) {
			return globalData.template_comment.replace(/{{id}}/g, data.id).replace(/{{name}}/g, data.name).replace(/{{src}}/g, data.src).replace(/{{content}}/g, data.content)
		},

		//评论拼接
		fillInComment: function (data, $father) {
			for(var i = 0; i < data.length; i++){
				var $item = $(funcControl.setCommentData(data[i]))
				$father.append($item)
			}
			funcControl.deleteComments()
		},
		
		//下一页评论加载
		nextComments: function () {
			var $more_comments = $('#more_comments')

			$more_comments.on('click',function () {
				if(globalData.more_comment_flag){
					return false
				}
				var $that = $(this)

				//在请求结束前不允许再次触发
				globalData.more_comment_flag = true

				$that.html('加载中')
				var $father = $that.siblings('.single-item-comments-item-wrap')

				/**
				* 请求更多评论
				* */
				/*$.ajax({
					url: globalData.host,
					data: {
						page: ++globalData.comment_page
					},
					success: function (res) {
						if(200 == res.data.code){
							funcControl.fillInComment(res.data.data, $father)
							$that.html('查看更多')
							globalData.more_comment_flag = false
						}else {
							alert(res.data.msg)
							$that.html('暂不可用')
						}
					}
				})*/

				//测试用，以下可删
				var test_data = [
					{
						id: 1,
						name: '小猫',
						src: 'http://img.sc115.com/tx/ns/cpic/1504axse4toevz5.jpg',
						content: '这是一条新的评论！'
					},
					{
						id: 2,
						name: '小狗',
						src: 'http://img.sc115.com/tx/ns/cpic/1504axse4toevz5.jpg',
						content: '这是第二条新的评论！'
					}
				]
				funcControl.fillInComment(test_data, $father)
				$that.html('查看更多')
				globalData.more_comment_flag = false
				//测试用，以上可删

			})
		},

		//广告列表全选按钮
		adAll: function () {
			var $ad_all_btn = $('#ad_all'),
				$checkbox = $ad_all_btn.find("input[type='checkbox']"),
				$ad_all = $('.ad-content')

			$ad_all_btn.on('click', function () {
				var check = $checkbox.prop('checked')
				if (check){
					$checkbox.prop('checked', false)
					$ad_all.prop('checked', false)
				}else {
					$checkbox.prop('checked', true)
					$ad_all.prop('checked', true)
				}
			})
		},

		//多选删除广告
		deleteAllad: function () {
			var $delete_btn = $('#del_all_ad')

			$delete_btn.on('click', function () {
				var $allAd = $('.ad-content'),
					data = []

				for(var i = 0; i < $allAd.length; i++){
					//保存被选中项的id
					if($($allAd[i]).prop('checked')){
						data.push($($allAd[i]).data('id'))
					}
				}
				if(data.length){
					/**
					 * 发送请求
					 * */
					/*if(confirm('确认删除所选广告吗？')){
					 $.ajax({
					 url: globalData.host,
					 method: 'POST',
					 data: data,
					 success: {

					 //重载页面
					 window.location.reload()
					 }
					 })
					 }*/
				}

			})
		},

		//单条广告删除
		deleteAd: function () {
			var $ad_del = $('.ad_del')

			$ad_del.on('click', function () {
				var $father = $(this).parents('table'),
					id = $(this).data('id')
				if(confirm('确认删除所选广告吗？')){
					/**
					 * 发送请求
					 * */
					/*
						 $.ajax({
							 url: globalData.host,
							 method: 'POST',
							 data: {
							    id: id
							 },
							 success: {
					            $father.remove()
							 }
						 })
					 */

					$father.remove()
					return false
				}
			})
		}

	}

	//启动程序
	octopus.init()
})