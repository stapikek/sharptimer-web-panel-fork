/**
 * @author stapi
 *
 * @link https://steamcommunity.com/id/stapi1337/
 * @link https://github.com/stapikek
*/    
        window.onscroll = function(){BoxShadows()};
        function BoxShadows(){
            let leaderboardinfo = document.querySelector(".leaderboard").getBoundingClientRect();
            if(leaderboardinfo.top < -1){
                document.querySelector(".info").classList.add("boxshadows");
            }else{
                document.querySelector(".info").classList.remove("boxshadows");
            }
        }

        var mappeno = document.querySelector(".mappeno");
        function toggleMaps(){
            mappeno.classList.toggle("invisible");
        }
        function openMode(e, modeName) {
            var i, content, tablink;
            content = document.getElementsByClassName("content");
            for (i = 0; i < content.length; i++) {
                content[i].style.display = "none";
            }
            tablink = document.getElementsByClassName("tablink");
            for (i = 0; i < tablink.length; i++) {
                tablink[i].className = tablink[i].className.replace(" active", "");
            }
            document.getElementById(modeName).style.display = "block";
            document.querySelector(".mappeno").style.display = "block";
            e.currentTarget.className += " active";

            if(mappeno.classList.contains("invisible")){
                mappeno.classList.remove("invisible");
            }
            else{
                
            }
        }


