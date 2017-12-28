
/**
	version 2.4
**/
function stop(ele) {
        ele.scrollAmount = 0;
}
function slow(ele) {
        ele.scrollAmount = 1;
}
function fast(ele) {
        ele.scrollAmount = 2;
}
function fast2(ele) {
        ele.scrollAmount = 3;
}

function check(flag){
        var cflag=false;
        var allflag=true;
        if(flag==1){
                for (var i=0;i<document.myform.elements.length;i++)
                {
                        var e=document.myform.elements[i];
                        if ((e.type=='checkbox'))
                        {
                                e.checked=document.myform.checkall.checked;
                        }
                }
        }

        for (var i=0;i<document.myform.elements.length;i++)
        {
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }

        if(allflag)
                document.myform.checkall.checked=true;
        else
                document.myform.checkall.checked=false;
}


function gofirst(cur){
        document.myform.curr.value=cur;
        document.myform.submit();
}

function golast(cur){
        document.myform.curr.value=cur;
        document.myform.submit();
}

function goprev(cur){
		document.myform.curr.value=cur;
        document.myform.submit();
}
function gonext(cur){
		document.myform.curr.value=cur;
        document.myform.submit();
}
function formsort(fsort,forder){
	document.myform.sort.value=fsort;
	document.myform.order.value=forder;
	document.myform.submit();
}
function formdelete(action,page){
	ret = confirm("Are you sure want to delete??");
	if (ret == true){
		document.myform.operation.value='delete';
		document.myform.p.value=page;
		document.myform.action=action;
		document.myform.submit();
	}
	return;
}
function kiratxt(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>maxlimit){
                field.value=field.value.substring(0,maxlimit);
		    str="Maximum " + maxlimit + " huruf";
                alert(str);
                return true;
        }else{
                countfield.value=maxlimit-y;
        }
}


function showhide(e1,e2)
{
	if(e1!=''){
		if(document.getElementById(e1).style.display == "none")
  			document.getElementById(e1).style.display = "block";
		else
			document.getElementById(e1).style.display = "none";
	}
	if(e2!=''){
		if(document.getElementById(e2).style.display == "none")
  			document.getElementById(e2).style.display = "block";
		else
			document.getElementById(e2).style.display = "none";
	}
}
function show(e1)
{
	document.getElementById(e1).style.display = "block";
}

function hide(e1)
{
	document.getElementById(e1).style.display = "none";
}

var newwin = "";
function newwindow(url,type) 
{ 
		if (newwin == "" || newwin.closed || newwin.name == undefined) {
			if(type){
    			newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
				var a = window.setTimeout("document.formwindow.submit();",500);
			}else{
				newwin = window.open(url,"newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
			} 
	  	} else{
    			newwin.focus();
  		}
}
function newwindow2(url,type) 
{ 
		if (newwin == "" || newwin.closed || newwin.name == undefined) {
			if(type){
				document.myform.action=url;
				document.myform.isprint.value="1";
				document.myform.target='newwindow';
    			newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
				var a = window.setTimeout("document.myform.submit();",500);
			}else{
				newwin = window.open(url,"newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
			} 
	  	} else{
    			newwin.focus();
  		}
}
