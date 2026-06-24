<style>
    iframe[src*="chatbase.co"],
    iframe[id*="chatbase"],
    #chatbase-bubble-button,
    #chatbase-bubble-window {
        /* Desktop: di atas navbar (z-55), di bawah dropdown (z-60) */
        z-index: 56 !important;
    }

    /* Mobile: di depan navbar (z-55), tapi di belakang sidebar (z-60) */
    @media (max-width: 767px) {
        iframe[src*="chatbase.co"],
        iframe[id*="chatbase"],
        #chatbase-bubble-button,
        #chatbase-bubble-window {
            z-index: 56 !important;
        }

        /* Memberikan jarak dengan navbar saat bubble window dibuka */
        #chatbase-bubble-window,
        iframe[id*="chatbase-bubble-window"],
        iframe[src*="chatbase.co"][id*="bubble-window"] {
            top: 76px !important;
            height: calc(100% - 76px) !important;
            max-height: calc(100vh - 76px) !important;
        }
    }
</style>
<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="Y2vILvNPd7OK5zbNrjNrA";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>
