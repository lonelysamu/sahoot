var current = 0;
var cs = 0;
const set = "set1";
var question = "." + set +"question";
var ans = "." + set +"ans";

document.querySelector('#contentbody').addEventListener('click', (e) => {
    if (e.target.classList.contains("questionbegin") == true) {

        document.getElementById("lobby").classList.add("fadetoright");
        document.querySelector('#contentbody').addEventListener("webkitAnimationEnd", (e) => {
            document.getElementById("lobby").classList.add("d-none");
            document.getElementById("lobby").classList.remove("fadetoright");
            document.querySelectorAll(question)[current].classList.remove("d-none");
            document.querySelectorAll(question)[current].classList.add("fadefromleft1");
        })
    }
    else if (e.target.classList.contains(ans) == true) {
        console.log(current)

        y = Array.from(document.querySelectorAll('.set1question')[current].getElementsByClassName(ans)).indexOf(e.target);
        
        if (current == 0) { if (y == 1) { cs = cs + 1; } }
        else if (current == 1) { if (y == 2) { cs = cs + 1; } }
        else if (current == 2) { if (y == 0) { cs = cs + 1; } }
        else if (current == 3) { if (y == 1) { cs = cs + 1; } }
        else if (current == 4) { if (y == 0) { cs = cs + 1; } }
        else if (current == 5) { if (y == 2) { cs = cs + 1; } }
        else if (current == 6) { if (y == 1) { cs = cs + 1; } }
        else if (current == 7) { if (y == 3) { cs = cs + 1; } }
        else if (current == 8) { if (y == 3) { cs = cs + 1; } }
        else if (current == 9) { if (y == 0) { cs = cs + 1; } }

        /* answer for set 2
        if (current == 0) { if (y == 2) { cs = cs + 1; } }
        else if (current == 1) { if (y == 2) { cs = cs + 1; } }
        else if (current == 2) { if (y == 1) { cs = cs + 1; } }
        else if (current == 3) { if (y == 2) { cs = cs + 1; } }
        else if (current == 4) { if (y == 1) { cs = cs + 1; } }
        else if (current == 5) { if (y == 3) { cs = cs + 1; } }
        else if (current == 6) { if (y == 2) { cs = cs + 1; } }
        else if (current == 7) { if (y == 0) { cs = cs + 1; } }
        else if (current == 8) { if (y == 1) { cs = cs + 1; } }
        else if (current == 9) { if (y == 1) { cs = cs + 1; } }
        */
        current = current + 1;
        document.querySelectorAll(question)[current - 1].classList.add("fadetoright");
        document.querySelector('#contentbody').addEventListener("webkitAnimationEnd", (e) => {
            document.querySelectorAll(question)[current - 1].classList.add("d-none");
            document.querySelectorAll(question)[current].classList.remove("d-none");
            document.querySelectorAll(question)[current].classList.add("fadefromleft1");
        })



    }

})


function removeall(item, index) {
    item.classList.add("d-none");
    item.classList.remove("fadein");
    item.classList.remove("fadefromleft2", "hidetillscroll", "fadefromleft1");
}