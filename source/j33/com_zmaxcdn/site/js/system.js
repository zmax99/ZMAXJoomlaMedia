jQuery(function($) {
$('.changeItemStateButton').click(function() {
	var item = ($(this));
		var id = item.attr("data");
		id=id.toString() ;
		
		var order = $("#orderno");
		var orderNo = order.val();		
		
		var state = $('#itemstate'+id+' option:selected');
		var newState = state.text();
		var url = "index.php?option=com_zmaxerp&task=orderitem.changeState&orderno="+orderNo+"&itemid="+id+"&state="+newState;
		
		htmlobj=$.ajax({url:url,async:false});
		alert(htmlobj.responseText);
		
		
});

$('.deleteItemButton').click(function() {
	var item = ($(this));
		var id = item.attr("data");
		id=id.toString() ;
		
		var order = $("#orderno");
		var orderNo = order.val();
		
		var url = "index.php?option=com_zmaxerp&task=orderitem.delete&orderno="+orderNo+"&itemid="+id;
		
		htmlobj=$.ajax({url:url,async:false});
		alert(htmlobj.responseText);
		
		window.location.reload();
		
});

$('.xiangshu_input').blur(function() {
	var item = ($(this));
		var id = item.attr("data");
		id=id.toString() ;
		
		var remark = $("#xiangshu"+id);
		var remark = remark.val();
		if(remark)
		{
			var url = "index.php?option=com_zmaxerp&task=orderitem.remark&remark="+remark+"&itemid="+id;
		
			htmlobj=$.ajax({url:url,async:false});
			alert(htmlobj.responseText);
		
			window.location.reload();
		}
		
		
});

});

