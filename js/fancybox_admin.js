jQuery(document).ready(function(){
	jQuery("#genrate").click(function(){
		var url=jQuery("#youtubeurl").val();
		var height=jQuery("#t_height").val();
		var width=jQuery("#t_width").val();
		if(url){
					var videoid=youtube_parser(url);
					var str='[youtube videoid="'+videoid+'"';
					
					if(height){
							str+=' height="'+height+'"';
						}
					if(width){
							str+=' width="'+width+'"';
						}
				str+=']'
		}
		jQuery("#shortcode").val(str);
		jQuery("#shortcode").select();
	});
	jQuery("#shortcode").click(function(){
		jQuery("#shortcode").select();
	});
});
function youtube_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
        alert("URL you entered might be wrong, Please enter correct URL !");
    }
}