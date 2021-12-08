var current = 0;
var cs = 0;
const set = "set1";
let answers = [];
let connect = new VW_Connect();
var one = 0;

// Quah, the initial bit i moved here, to this submit event 
document.getElementById("begin-form").addEventListener("submit",(e)=>{
    e.preventDefault();
    // if (e.target.classList.contains("questionbegin") == true) {
        var button = document.getElementById("questionBegin-btn");
        button.disabled = true;
        var span = document.createElement("span");
        span.id = 'loader-span';
        span.classList.add('fa','fa-spinner','fa-pulse');
        button.prepend(span);

        connect.POST(
            "/assets/php/score.php",
            {a:"REG",phone:document.getElementById("phonenumber").value},
            (a,b) => {            
                if(b.status) {
                    // Quah, your code when you press start should be here
                    document.getElementById("lobby").classList.add("fadetoright");
                    document.querySelector('#contentbody').addEventListener("webkitAnimationEnd", (e) => {
                        document.getElementById("lobby").classList.add("d-none");
                        document.getElementById("lobby").classList.remove("fadetoright");
                        document.querySelectorAll(`.${set}question`)[current].classList.remove("d-none");
                        document.querySelectorAll(`.${set}question`)[current].classList.add("fadefromleft1");
                    })
                } else {
                    alert("Thank you for trying the quiz!");
                    button.removeChild(document.getElementById("loader-span"));
                    button.disabled = false;
                }

            }
        )
    // }
})

document.querySelector('#contentbody').addEventListener('click', (e) => {
    
    if (e.target.classList.contains(`${set}ans`) == true) {
        console.log(current)

        y = Array.from(document.querySelectorAll(`.${set}question`)[current].getElementsByClassName(`${set}ans`)).indexOf(e.target);
        answers[current] = y;

        // if (set == "set1") {
        //     if (current == 0) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 1) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 2) { if (y == 0) { cs = cs + 1; } }
        //     else if (current == 3) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 4) { if (y == 0) { cs = cs + 1; } }
        //     else if (current == 5) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 6) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 7) { if (y == 3) { cs = cs + 1; } }
        //     else if (current == 8) { if (y == 3) { cs = cs + 1; } }
        //     else if (current == 9) { if (y == 0) { cs = cs + 1; } }
        // }
        // else {
        //     if (current == 0) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 1) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 2) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 3) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 4) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 5) { if (y == 3) { cs = cs + 1; } }
        //     else if (current == 6) { if (y == 2) { cs = cs + 1; } }
        //     else if (current == 7) { if (y == 0) { cs = cs + 1; } }
        //     else if (current == 8) { if (y == 1) { cs = cs + 1; } }
        //     else if (current == 9) { if (y == 1) { cs = cs + 1; } }
        // }
        current = current + 1;
        document.querySelectorAll(`.${set}question`)[current - 1].classList.add("fadetoright");
        document.querySelector('#contentbody').addEventListener("webkitAnimationEnd", (e) => {
            document.querySelectorAll(`.${set}question`)[current - 1].classList.add("d-none");
            document.querySelectorAll(`.${set}question`)[current].classList.remove("d-none");
            document.querySelectorAll(`.${set}question`)[current].classList.add("fadefromleft1");

            if(current > 9 && one == 0){ 
                one =1;
                connect.POST(
                    "/assets/php/score.php",
                    {
                        a:"SCORE",
                        name : document.getElementById("name").value,
                        phone:document.getElementById("phonenumber").value,
                        set : set,
                        ans : answers 
                    },
                    (a,b) => {            
                        if(b.status) {
                            alert(`Thank you ! Your score is ${b.score} !`);
                        } else {
                            console.error(b);
                        }
        
                    }
                )
            }
        })



    }

})
