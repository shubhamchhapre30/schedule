!function(t,a){var e=1e3,i=!1,n=t([]),o=function(){s.resume()},r=function(e,i){var o=e.data("livestampdata");if("number"==typeof i&&(i*=1e3),e.removeAttr("data-livestamp").removeData("livestamp"),i=a(i),a.isMoment(i)&&!isNaN(+i)){var r=t.extend({},{original:e.contents()},o);r.moment=a(i),e.data("livestampdata",r).empty(),n.push(e[0])}},m=function(){i||(s.update(),setTimeout(m,e))},s={update:function(){t("[data-livestamp]").each(function(){var a=t(this);r(a,a.data("livestamp"))});var e=[];n.each(function(){var i=t(this),n=i.data("livestampdata");if(void 0===n)e.push(this);else if(a.isMoment(n.moment)){var o=i.html(),r=n.moment.fromNow();if(o!=r){var m=t.Event("change.livestamp");i.trigger(m,[o,r]),m.isDefaultPrevented()||i.html(r)}}}),n=n.not(e)},pause:function(){i=!0},resume:function(){i=!1,m()},interval:function(t){return void 0===t?e:void(e=t)}},u={add:function(e,i){return"number"==typeof i&&(i*=1e3),i=a(i),a.isMoment(i)&&!isNaN(+i)&&(e.each(function(){r(t(this),i)}),s.update()),e},destroy:function(a){return n=n.not(a),a.each(function(){var e=t(this),i=e.data("livestampdata");return void 0===i?a:void e.html(i.original?i.original:"").removeData("livestampdata")}),a},isLivestamp:function(t){return void 0!==t.data("livestampdata")}};t.livestamp=s,t(o),t.fn.livestamp=function(t,a){return u[t]||(a=t,t="add"),u[t](this,a)}}(jQuery,moment);