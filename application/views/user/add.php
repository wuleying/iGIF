<?php $this->load->view('layout/header'); ?>

<script type="text/javascript" src="<?php echo base_url('/js/plupload/plupload.full.min.js'); ?>"></script>

<div class="well well-lg margin12">
	<form method="post" action="<?php echo base_url('/user/add'); ?>">
		<input type="hidden" name="action" value="1" />
		<p>
		<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
		<div id="container">
			<a id="pickfiles" class="btn btn-primary" href="javascript:;">选择文件</a>
			<a id="uploadfiles" class="btn btn-primary" href="javascript:;">上传</a>
		</div>
		<div id="console"></div>
		</p>
		<p>
			<label for="description">描述</label>
			<textarea name="description" id="description" class="form-control" rows="5"></textarea>
		</p>
		<p><button type="submit" class="btn btn-lg btn-primary btn-block">确定</button></p>
	</form>
</div>

<script type="text/javascript">
	var uploader = new plupload.Uploader({
		runtimes: 'html5,flash,silverlight,html4',
		browse_button: 'pickfiles',
		container: document.getElementById('container'),
		url: '<?php echo base_url('/upload'); ?>',
		flash_swf_url: '<?php echo base_url('/js/plupload/Moxie.swf'); ?>',
		silverlight_xap_url: '<?php echo base_url('/js/plupload/Moxie.xap'); ?>',
		filters: {
			max_file_size: '10mb',
			mime_types: [
				{title: "Image files", extensions: "gif"},
			]
		},
		init: {
			PostInit: function() {
				document.getElementById('filelist').innerHTML = '';

				document.getElementById('uploadfiles').onclick = function() {
					uploader.start();
					return false;
				};
			},
			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
				});
			},
			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},
			Error: function(up, err) {
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			}
		}
	});

	uploader.init();

</script>

<?php $this->load->view('layout/footer'); ?>