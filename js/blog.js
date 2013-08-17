
function deleteBlog() {
	var id = $(this).data('blog');
	var context = $(this).parent();
	auth("blog", function(data) {
		if (confirm("Are you sure you want to delete this blog? It will delete all posts along with it.")) 
			$.ajax("/blog/"+id+"/destroy").complete(function (data) {
				$("#blog").html("Deleted!");
			});
	});
}

function saveEditBlog() {
	$.ajax("/blog/"+$("#blog").data("id")+"/edit",{
		type: "POST",
		data: {"title":$("#edit-title").val(),"desc":$("#edit-desc").val()},
		complete : function(data) {
			data = $.parseJSON(data.responseText);
			$("#blog-title").empty().append(data.title);
			$("#blog-desc").empty().append(data.desc);
			$("#edit-save").remove();
			$("#edit").show();
		}
	});
}
function editBlog() {
	$("#edit").hide();
	auth("blog",function() {
		var title = $("#blog-title").html();
		var desc = $("#blog-desc").html();
		var id = $("#blog").data("id");
		$("#blog-title").empty().append("<input id='edit-title' value='"+title+"'>");
		$("#blog-desc").empty().append("<textarea id='edit-desc'>"+desc+"</textarea>");
		$("#blog-desc").append("<button id='edit-save'>Save</button>");
		$("#blog-desc").append("<button id='delete-blog' data-blog='"+id+"'>Delete</button>");
		$("#edit-save").click(saveEditBlog);
		$("#delete-blog").click(deleteBlog);
	});

}

function makePost() {
	var blog = $("#blog").data('id');
	$.ajax("/blog/"+blog+"/posts/create", {
		type:"POST",
		data:{"title":$("#new-title").val(),"body":$("#new-body").val()},
		complete : function(data) {
			if ($("#posts").data("page") != 1) 
				$("#posts").prepend("<center>Your post has been made <a href='/blog/"+blog+"'>on the first page</a>.</center>");
			else {
				$("#posts").prepend(data.responseText);
				$("#post .control").click(editPost);
			}
			$("#new-edit").empty();
			$("#new .control").show();
		}
	});
}

function newPost() {
	auth("blog",function() {
		$("#new .control").hide();
		$("#new-edit").empty().prepend("Title: <br><input id='new-title'><br>Body:<br><textarea id='new-body'></textarea><br><button id='makepost'>Make New Post</button>");
		$("#makepost").click(makePost);
	});
}

function saveNewBlog() {
	$.ajax("/blog/create",{
		type:"POST",
		data: {"title":$("#add-title").val(),"desc":$("#add-desc").val()},
		complete : function (data) {
			$("#add-form").remove();
			$("#blogs").append(data.responseText);
		}
	});
}

function addBlog() {
	$.ajax("/auth/blog").complete(function(data) {
			if (data.status == 200) {
				$("#add-blog").append("<div id='add-form'>Title: <input id='add-title'> <br>Description<br><textarea id='add-desc'></textarea><button id='add-save'>Add Blog</button></div>");
				$("#add-save").click(saveNewBlog);
			}
		});
}

function savePost() {
	var blog = $(this).data('blog');
	var id = $(this).data('id');
	$.ajax("/blog/"+blog+"/posts/"+id+"/edit",{
		dataType:"json",
		type: "POST",
		data: { "title" : $("#edit-title-"+id).val(), "body" : $("#edit-body-"+id).val()},
		complete : function (data) {
			data = $.parseJSON(data.responseText);
			$("#title-"+id).empty().append("<b>"+data.title+"</b>");
			$("#body-"+id).empty().append(data.body);
			$("#save").remove();
			$("#delete").remove();
			$("#post .control").show();
		}
	});
}

function deletePost() {	
	var blog = $(this).data('blog');
	var id = $(this).data('id');
	if (confirm("Are you sure?"))
		$.ajax("/blog/"+blog+"/posts/"+id+"/destroy/").complete(function (data) {
			$(".post-"+id).remove();
			$("#delete").remove();
			$("#post .control").show();	
		});
}

function editPost() {
	$("#post .control").hide();
	var id = $(this).data('id');
	var title = $("#title-"+id+ " b").html()
	var body = $("#body-"+id).html();
	var context = $(this);
	var blog = $("#blog").data('id');
	auth("blog",function(data) {
		$("#title-"+id).empty().append("<input id='edit-title-"+id+"' value='"+title+"'>");
		$("#body-"+id).empty().append("<textarea id='edit-body-"+id+"'>"+body+"</textarea>");
		$(context).parent().append("<button data-id='"+id+"' data-blog='"+blog+"' id='save'>Save</button>");
		$(context).parent().append("<button data-id='"+id+"' data-blog='"+blog+"' id='delete'>Delete</button>");
		$("#delete").click(deletePost);
		
		$("#save").click(savePost);
	});
}

$(document).ready(function() {
	$("#new .control").dblclick(newPost);
	$("#edit").dblclick(editBlog);
	$("#add-blog .control").dblclick(addBlog);
	$("#post .control").dblclick(editPost);
	$("#blog-thumb h4").hover(function() {
		$(this).css('text-decoration','underline');
	},function() {
		$(this).css('text-decoration','none');
	})
	$("#blog-thumb h4").click(function() {
		if ($(this).siblings().first().is(':hidden')) {
			$("#blog-thumb p").slideUp();
			$(this).siblings().first().slideDown();
		}
	});
});