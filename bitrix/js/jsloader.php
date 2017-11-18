<?
	CJSCore::Init(array('jquery'));
	$pathToJQ = CJSCore::getExtInfo('jquery');
	$pathToJQ = $pathToJQ['js'];
?>
<script type='text/javascript'>
	if(typeof IPOL_JSloader == 'undefined')
	var IPOL_JSloader = {
		jqInited: false,

		bindReady: function(handler){
			var called = false;
			function ready(){
				if (called) return;
				called = true;
				handler();
			}
			if(document.addEventListener){
				document.addEventListener("DOMContentLoaded",function(){
					ready();
				}, false);
			}else if(document.attachEvent) {
				if (document.documentElement.doScroll && window == window.top){
					function tryScroll(){
						if (called) return;
						if (!document.body) return;
						try {
							document.documentElement.doScroll("left");
							ready();
						}catch(e){
							setTimeout(tryScroll,0);
						}
					}
					tryScroll();
				}
				document.attachEvent("onreadystatechange", function(){
					if (document.readyState === "complete"){
						ready();
					}
				});
			}
			if (window.addEventListener)
				window.addEventListener('load', ready, false);
			else if (window.attachEvent)
				window.attachEvent('onload', ready);
		},

		loadScript: function(src,ifJQ){
			var loadedJS = document.createElement('script');
			loadedJS.src = src;
			loadedJS.type = "text/javascript";
			loadedJS.language = "javascript";
			var head = document.getElementsByTagName('head')[0];
			head.appendChild(loadedJS);
			if(typeof(ifJQ) !== 'undefined'){
				loadedJS.onload = IPOL_JSloader.recall;
				loadedJS.onreadystatechange = function () { //  IE
					 if (this.readyState == 'complete' || this.readyState == 'loaded')
						  IPOL_JSloader.recall();
				};
			}
		},

		loadJQ: function(){
			IPOL_JSloader.loadScript('<?=$pathToJQ?>',true);
			jqInited = true;
		},

		recalled: [],
		checkScript: function(checker,src){
			IPOL_JSloader.recalled.push([checker,src]);
			if(!IPOL_JSloader.jqInited && !IPOL_JSloader.checkJQ())
				IPOL_JSloader.loadJQ();
			else
				IPOL_JSloader.recall();
		},

		checkLoadJQ: function(){
			if(!IPOL_JSloader.jqInited && !IPOL_JSloader.checkJQ())
				IPOL_JSloader.loadJQ();
		},

		checkJQ: function(){
			return(typeof($) != 'undefined' && typeof($('body').html) != 'undefined');
		},

		recall: function(){
			if(IPOL_JSloader.recalled.length == 0) return;
			else
				for(var i in IPOL_JSloader.recalled){
					if(!IPOL_JSloader.recalled[i][0] || typeof(eval(IPOL_JSloader.recalled[i][0])) == 'undefined')
						IPOL_JSloader.loadScript(IPOL_JSloader.recalled[i][1]);
					delete(IPOL_JSloader.recalled[i]);
				}
		}
	};
</script>