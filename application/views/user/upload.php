<script type="text/javascript" src="<?php echo base_url('/js/plupload/plupload.full.min.js'); ?>"></script>
<div class="well well-lg margin12">
	<form method="post" action="<?php echo base_url('/user/doupload'); ?>" onsubmit="return checkForm();">
		<input type="hidden" name="path" id="gifpath" value="" />
		<p class="uploader-box">
		<div id="filelist"></div>
		<div id="container">
			<button id="pickfiles" class="btn btn-warning">选择图片</button>
		</div>
		<div id="console"></div>
		</p>
		<p>
			<label for="description">简介</label>
			<textarea name="description" id="description" class="form-control" rows="5"></textarea>
		</p>
		<p><button type="submit" id="submit" class="btn btn-lg btn-primary btn-block">提交</button></p>
		<div id="message" class="alert alert-danger hidden"></div>
	</form>
</div>

<script type="text/javascript">
		var uploader = new plupload.Uploader({
			runtimes: 'html5,flash,silverlight,html4',
			browse_button: 'pickfiles',
			container: document.getElementById('container'),
			url: '<?php echo base_url('/uploadimage'); ?>',
			flash_swf_url: '<?php echo base_url('/js/plupload/Moxie.swf'); ?>',
			silverlight_xap_url: '<?php echo base_url('/js/plupload/Moxie.xap'); ?>',
			filters: {
				max_file_size: '10mb',
				mime_types: [
					{title: "Image files", extensions: "gif,png"},
				]
			},
			init: {
				PostInit: function() {
					$("#filelist").html('');
				},
				FilesAdded: function(up, files) {
					plupload.each(files, function(file) {
						$("#pickfiles").removeClass("btn-warning");
						var html = '<div class="progress progress-striped">';
						html += '<div id="progress" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">';
						html += '</div>';
						html += '<div id="uploadinfo"></div>';
						html += '</div>';
						$("#filelist").html(html);
						$("#uploadinfo").html('<div id="' + file.id + '"><span>' + file.name + ' [' + plupload.formatSize(file.loaded) + '/' + plupload.formatSize(file.size) + ']</span><strong>' + file.percent + '%</strong></div>');
						uploader.start();
					});
				},
				UploadProgress: function(up, file) {
					$("#progress").css('width', file.percent + '%');
					$("#uploadinfo").html('<div id="' + file.id + '"><span>' + file.name + ' [' + plupload.formatSize(file.loaded) + '/' + plupload.formatSize(file.size) + ']</span><strong>' + file.percent + '%</strong></div>');
				},
				FileUploaded: function(up, file, info) {
					var result = eval('(' + info.response + ')');
					$("#gifpath").attr("value", result.path);
				},
				Error: function(up, err) {
					$("#message").html("出错了：" + err.message);
					$('#filelist').html('');
					$('#uploadfiles').addClass('disable');
					up.refresh();
					if (up.files.length > 0)
					{
						up.removeFile(up.getFile(up.files[up.files.length - 1].id));
					}
				}
			}
		});

		// 检查表单
		function checkForm()
		{
			if ($("#gifpath").attr("value") == "")
			{
				$("#pickfiles").addClass("btn-warning");
				$("#message").removeClass("hidden");
				$("#message").html("请选择图片");
				return false;
			}

			if ($("#description").val() == "")
			{
				$("#message").removeClass("hidden");
				$("#message").html("请填写简介");
				$("#description").css("border-color", "red");
				$("#description").focus();
				return false;
			}
			return true;
		}

		uploader.init();
</script>
<?php $this->load->view('layout/footer'); ?>