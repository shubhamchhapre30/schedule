!function(e){function t(t,i){var s=e(t).text().toUpperCase(),l=e(i).text().toUpperCase();return l>s?-1:s>l?1:0}var i={init:function(i){var s={selectedPosition:"right",moveOptions:!0,labelTop:"Top",labelBottom:"Bottom",labelUp:"Up",labelDown:"Down",labelSort:"Sort",labelsx:"Available",labeldx:"Selected",maxSelected:-1,autoSort:!1,autoSortAvailable:!1,search:!1,caseSensitive:!1,delay:200,optGroupSearch:!1,minSize:6,seloption:!0};return this.each(function(){var l=e(this),o=l.data("multiselect2side");i&&e.extend(s,i),o||l.data("multiselect2side",s);var n=e(this).attr("name");-1!=n.indexOf("[")&&(n=n.substring(0,n.indexOf("[")));var a=n+"ms2side__dx",d=n+"ms2side__sx",r=e(this).attr("size");r<s.minSize&&(e(this).attr("size",""+s.minSize),r=s.minSize);var c="<div class='ms2side__updown'><p class='SelSort' title='Sort'>"+s.labelSort+"</p><p class='MoveTop' title='Move on top selected option'>"+s.labelTop+"</p><p class='MoveUp' title='Move up selected option'>"+s.labelUp+"</p><p class='MoveDown' title='Move down selected option'>"+s.labelDown+"</p><p class='MoveBottom' title='Move on bottom selected option'>"+s.labelBottom+"</p></div>",h=!1,p=!1;if(0!=s.search&&0!=s.optGroupSearch){var v=s.optGroupSearch+"<select class='small' ><option value=__null__> </option></select> "+s.search+"<input class='small' type='text' /><a href='#'> </a>";"right"==s.selectedPosition?h=v:p=v}else if(0!=s.search){var v=s.search+"<input type='text' /><a href='#'> </a>";"right"==s.selectedPosition?h=v:p=v}else if(0!=s.optGroupSearch){var v=s.optGroupSearch+"<select><option value=__null__> </option></select>";"right"==s.selectedPosition?h=v:p=v}var m="<div class='ms2side__div'>"+("right"!=s.selectedPosition&&s.moveOptions?c:"")+"<div class='ms2side__select'>"+(s.labelsx||0!=h?"<div class='ms2side__header'>"+(0!=h?h:s.labelsx)+"</div>":"")+"<select title='"+s.labelsx+"' name='"+d+"' id='"+d+"' size='"+r+"' multiple='multiple' ></select></div><div class='ms2side__options'>"+("right"==s.selectedPosition?"<p class='AddOne' title='Add Selected'>&rsaquo;</p><p class='AddAll' title='Add All'>&raquo;</p><p class='RemoveOne' title='Remove Selected'>&lsaquo;</p><p class='RemoveAll' title='Remove All'>&laquo;</p>":"<p class='AddOne' title='Add Selected'>&lsaquo;</p><p class='AddAll' title='Add All'>&laquo;</p><p class='RemoveOne' title='Remove Selected'>&rsaquo;</p><p class='RemoveAll' title='Remove All'>&raquo;</p>")+"</div><div class='ms2side__select'>"+(s.labeldx||0!=p?"<div class='ms2side__header'>"+(0!=p?p:s.labeldx)+"</div>":"")+"<select title='"+s.labeldx+"' name='"+a+"' id='"+a+"' size='"+r+"' multiple='multiple' ></select></div>"+("right"==s.selectedPosition&&s.moveOptions?c:"")+"</div>";l.after(m).hide();var f=l.next().children(".ms2side__select").children("select"),u="right"==s.selectedPosition?f.eq(0):f.eq(1),_="right"==s.selectedPosition?f.eq(1):f.eq(0),g=e(".ms2side__select").eq(0).height(),x=e(),A=e(this).next().find("input:text"),S=A.next().hide(),b=!1,C=!1;if(0!=s.optGroupSearch){var T=!1;x=e(this).next().find("select").eq(0),l.children("optgroup").each(function(){0==x.find("[value='"+e(this).attr("label")+"']").size()&&x.append("<option value='"+e(this).attr("label")+"' >"+e(this).attr("label")+"</option>")}),x.change(function(){var t=e(this);t.val()!=T&&(""!=A.val()&&(clearTimeout(b),S.hide(),A.val(""),C=""),setTimeout(function(){"__null__"==t.val()?els=l.find("option:not(:selected)"):els=l.find("optgroup[label='"+t.val()+"']").children("option:not(:selected)"),u.find("option").remove(),els.each(function(){u.append(e(this).clone())}),T=t.val(),u.trigger("change")},100))})}var w=function(){var t=(u.children(),l.find("option:not(:selected)"));T="__null__",x.val("__null__"),C!=A.val()&&(A.addClass("wait").removeAttr("style"),C=A.val(),setTimeout(function(){u.children().remove(),""==C?(t.clone().appendTo(u).removeAttr("selected"),S.hide()):(t.each(function(){var t=e(this).text();s.caseSensitive?find=t.indexOf(C):find=t.toUpperCase().indexOf(C.toUpperCase()),-1!=find&&e(this).clone().appendTo(u).removeAttr("selected")}),0==u.children().length&&A.css({border:"1px red solid"}),S.show(),u.trigger("change")),u.trigger("change"),A.removeClass("wait")},5))};S.click(function(){return clearTimeout(b),A.val(""),w(),!1}),A.keyup(function(){clearTimeout(b),b=setTimeout(w,s.delay)}),e(this).next().find(".ms2side__options, .ms2side__updown").each(function(){var t=g/2-e(this).height()/2;t>0&&e(this).css("padding-top",t+"px")}),e(this).find("option:selected").clone().appendTo(_),e(this).find("option:not(:selected)").clone().appendTo(u),e.browser.msie&&"6.0"==e.browser.version||_.children().removeAttr("selected");var M=0;s.autoSort&&f.change(function(){var i=_.find("option");i.length!=M&&(i.sort(t),l.find("option:selected").remove(),i.each(function(){_.append(e(this).clone()),e(this).appendTo(l).attr("selected",!0)}),M=i.length,_.children().removeAttr("selected"))});var z=0;s.autoSortAvailable&&f.change(function(){var i=u.find("option");i.length!=z&&(i.sort(t),u.find("option").remove(),i.each(function(){u.append(e(this).clone())}),z=i.length)}),f.change(function(){e.browser.msie&&"6.0"==e.browser.version&&l.show().hide();var t=e(this).parent().parent(),i=u.children(),o=_.children(),n=u.find("option:selected"),a=_.find("option:selected");0==n.size()||s.maxSelected>=0&&n.size()+o.size()>s.maxSelected?t.find(".AddOne").addClass("ms2side__hide"):t.find(".AddOne").removeClass("ms2side__hide"),t.find(".RemoveOne, .MoveUp, .MoveDown, .MoveTop, .MoveBottom, .SelSort").addClass("ms2side__hide"),o.size()>1&&t.find(".SelSort").removeClass("ms2side__hide"),a.size()>0&&(t.find(".RemoveOne").removeClass("ms2side__hide"),a.size()<o.size()&&(a.val()!=o.val()&&t.find(".MoveUp, .MoveTop").removeClass("ms2side__hide"),a.last().val()!=o.last().val()&&t.find(".MoveDown, .MoveBottom").removeClass("ms2side__hide"))),0==i.size()||s.maxSelected>=0&&i.size()>=s.maxSelected?t.find(".AddAll").addClass("ms2side__hide"):t.find(".AddAll").removeClass("ms2side__hide"),0==o.size()?t.find(".RemoveAll").addClass("ms2side__hide"):t.find(".RemoveAll").removeClass("ms2side__hide")}),u.dblclick(function(){e(this).find("option:selected").each(function(t,i){(s.maxSelected<0||_.children().size()<s.maxSelected)&&(e(this).remove().appendTo(_),l.find("[value='"+e(i).val()+"']").remove().appendTo(l).attr("selected",!0))}),e(this).trigger("change")}),_.dblclick(function(){e(this).find("option:selected").each(function(t,i){e(this).remove().appendTo(u),l.find("[value='"+e(i).val()+"']").removeAttr("selected").remove().appendTo(l)}),e(this).trigger("change"),x.val("__null__").trigger("change"),S.click()}),e(this).next().find(".ms2side__options").children().click(function(){e(this).hasClass("ms2side__hide")||(e(this).hasClass("AddOne")?u.find("option:selected").each(function(t,i){e(this).remove().appendTo(_),l.find("[value='"+e(i).val()+"']").remove().appendTo(l).attr("selected",!0)}):e(this).hasClass("AddAll")?S.is(":visible")||x.length>0&&"__null__"!=x.val()?u.children().each(function(t,i){e(this).remove().appendTo(_),l.find("[value='"+e(i).val()+"']").remove().appendTo(l).attr("selected",!0)}):(u.children().remove().appendTo(_),l.find("option").attr("selected",!0)):e(this).hasClass("RemoveOne")?(_.find("option:selected").each(function(t,i){e(this).remove().appendTo(u),l.find("[value='"+e(i).val()+"']").remove().appendTo(l).removeAttr("selected")}),S.click(),x.val("__null__").trigger("change")):e(this).hasClass("RemoveAll")&&(_.children().appendTo(u),_.children().remove(),l.find("option").removeAttr("selected"),S.click(),x.val("__null__").trigger("change"))),u.trigger("change")}),e(this).next().find(".ms2side__updown").children().click(function(){var i=_.find("option:selected"),s=_.find("option");if(!e(this).hasClass("ms2side__hide"))if(e(this).hasClass("SelSort"))s.sort(t),l.find("option:selected").remove(),s.each(function(){_.append(e(this).clone().attr("selected",!0)),l.append(e(this).attr("selected",!0))});else if(e(this).hasClass("MoveUp")){var o=i.first().prev(),n=l.find("[value='"+o.val()+"']");i.each(function(){e(this).insertBefore(o),l.find("[value='"+e(this).val()+"']").insertBefore(n)})}else if(e(this).hasClass("MoveDown")){var a=i.last().next(),d=l.find("[value='"+a.val()+"']");i.each(function(){e(this).insertAfter(a),l.find("[value='"+e(this).val()+"']").insertAfter(d)})}else if(e(this).hasClass("MoveTop")){var r=s.first(),c=l.find("[value='"+r.val()+"']");i.each(function(){e(this).insertBefore(r),l.find("[value='"+e(this).val()+"']").insertBefore(c)})}else if(e(this).hasClass("MoveBottom")){var h=s.last(),p=l.find("[value='"+h.val()+"']");i.each(function(){h=e(this).insertAfter(h),p=l.find("[value='"+e(this).val()+"']").insertAfter(p)})}u.trigger("change")}),e(this).next().find(".ms2side__options, .ms2side__updown").children().hover(function(){e(this).addClass("ms2side_hover")},function(){e(this).removeClass("ms2side_hover")}),u.trigger("change"),e(this).next().show()})},destroy:function(){return this.each(function(){var t=e(this),i=t.data("multiselect2side");i&&t.show().next().remove()})},addOption:function(t){var i={name:!1,value:!1,selected:!1};return this.each(function(){var s=e(this),l=s.data("multiselect2side");if(l){t&&e.extend(i,t);var o="<option value='"+i.value+"' "+(l.seloption?"selected":"")+" >"+i.name+"</option>";s.append(o);var n=s.next().children(".ms2side__select").children("select"),a="right"==l.selectedPosition?n.eq(0):n.eq(1),d="right"==l.selectedPosition?n.eq(1):n.eq(0);i.selected?d.append(o).trigger("change"):a.append(o).trigger("change")}})}};e.fn.multiselect2side=function(t){return i[t]?i[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist on jQuery.multiselect2side"):i.init.apply(this,arguments)}}(jQuery);