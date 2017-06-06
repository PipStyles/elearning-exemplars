// JavaScript Document
BASE = '/tandl/elearning/exemplars/admin';

function launchMMEdit(cat, id, destCat)
{
	window.open(BASE+'/mmedit/show/cat/'+cat+'/id/'+id+'/destcat/'+destCat , 'mmedit', 'scrollbars=yes,width=400px,height=700px;');
}


function launchImageEdit(url)
{
	window.open(url,'imageEdit','scrollbars=yes,width=1020px,height=740px;');
	imageEdit.focus();
}


function closeLaunched()
{
	opener.location.reload();
	self.close();
}