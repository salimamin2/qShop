<?php echo $header; ?>

<div class="breadcrumbs">
    <?php foreach($breadcrumbs as $i=>$breadcrumb): ?>
        <?php echo $breadcrumb['separator'] ? '<span>'.$breadcrumb['separator'].'</span>' : '' ; ?>
        <a class="<?php echo count($breadcrumbs)-1 == $i ? 'last': ''; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
</div>

<link href="catalog/view/javascript/jquery/camera/css/camera.css" type="text/css" rel="stylesheet" />

<script src="catalog/view/javascript/jquery/camera/jquery.mobile.customized.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/jquery/camera/camera.min.js" type="text/javascript"></script>

<div id="ColumnLeft">
	<h1><?php echo $heading_title; ?></h1>
	
	<ul class="list-collection">
	<?php foreach($collections as $i => $collection): ?>
		<li class="<?php echo $i == 0 ? ' active' : ''; ?>">
			<a href="javascript:void(0);" data-id="<?php echo $collection['id']; ?>"><?php echo $collection['title']; ?></a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>

<div id="Content" class="content-75 collection-info">
	<div class="collection-content">
		<h2 class="f-title"><?php echo $title; ?></h2>
		
		<div class="right-panel">
			<?php if($media_type == 'image' && !empty($medias)): ?>
				<div id="list-images" class="camera_wrap camera_beige_skin">
					<?php foreach($medias as $i => $media): ?>
						<div data-src="<?php echo $media['media']; ?>">
						</div>
					<?php endforeach; ?>
				</div>
			<?php elseif($media_type == 'video' && $medias['media'] != ''): ?>
				<?php echo $medias['media']; ?>
			<?php elseif(isset($image)): ?>
				<img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" />
			<?php endif; ?>
		</div>
	</div>

	<ul class="list-collection">
	<?php foreach($collections as $i => $collection): ?>
		<li class="<?php echo $i == 0 ? ' active' : ''; echo $i % 2 == 1 ? ' last' : ''; ?>">
			<a href="javascript:void(0);" data-id="<?php echo $collection['id']; ?>">
				<div class="active-overlay"></div>
				<img src="<?php echo $collection['image']; ?>" alt="<?php echo $collection['title']; ?>" />
				<h2><?php echo $collection['title']; ?></h2>
			</a>
		</li>
		<?php echo $i % 2 == 1 ? '<li class="clr"></li>' : ''; ?>
	<?php endforeach; ?>
	</ul>
	
	
	<script type="text/javascript">
	
		jQuery(function($){
			$('#list-images').camera({
				height: '599px',
				loader: 'pie',
				pagination: false,
				thumbnails: false,
				loaderColor: '#ffffff',
				loaderBgColor: '#d1cabe',
				loaderOpacity: 1,
				loaderPadding: 0,
				navigationHover: false,
				playPause: false,
				pieDiameter: 70
			});
		});

		$('.list-collection li a').on('click', function(){
			var $id = $(this).attr('data-id');
			$('.list-collection li').removeClass('active');
			$('.list-collection li a[data-id='+$id+']').parent('li').addClass('active');
			$.ajax({
				url: 'index.php?route=information/collection/getCollection&collection_id='+$id,
				type: 'GET',
				dataType:"json",
				beforeSend: function(){
					$('.collection-info .collection-content').append('<div class="loader" />');
				},
				complete: function(){
					$('.collection-info .loader').remove();
				},
				success: function(data){
					data = $.parseJSON(JSON.stringify(data));
					$('.collection-info .collection-content').hide();
					$('.collection-info .f-title').html(data.title);
					$('.collection-info .f-description').html(data.description);
					
					var html_right = '';
					if(data.media_type == 'image' && data.medias != ''){
						html_right += '<div id="list-images" class="camera_wrap camera_beige_skin">';
						$.each(data.medias, function(i, media){
							html_right += '<div data-src="'+media.media+'"></div>';
						});
						html_right += '</div>';
						
					} else if(data.media_type == 'video' && data.medias.media != '') {
						html_right += data.medias.media;
					} else if(data.image) {
						html_right += '<img src="'+data.image+'" alt="'+data.title+'" />';
					}
					
					$('.collection-info .right-panel').html(html_right);
					
					$('.collection-info .collection-content').fadeIn(500);
					if(data.media_type == 'image' && data.medias.length > 0){
						$('#list-images').camera({
							height: '599px',
							loader: 'pie',
							pagination: false,
							thumbnails: false,
							loaderColor: '#ffffff',
							loaderBgColor: '#d1cabe',
							loaderOpacity: 1,
							loaderPadding: 0,
							navigationHover: false,
							playPause: false,
							pieDiameter: 70
						});
					}
					
					goToByScroll('#Content',$('#Content').offset().top-$(".top-menu-fixed").innerHeight());
				}
			});
		});
	</script>
</div>
<?php echo $footer; ?>