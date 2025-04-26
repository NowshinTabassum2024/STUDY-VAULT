let body=document.body;
let profile = document.querySelector('header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
    profile.classList.toggle('active'); 
    searchForm.classList.remove('active');
} 
let searchForm = document.querySelector('.header .flex .search-form');
document.querySelector('#search-btn').onclick= () =>{ 
    searchForm.classList.toggle('active'); 
    profile.classList.remove('active')
}
let sideBar = document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick = () =>{ 
    sideBar.classList.toggle('active');
    body.classList.toggle('active');
}

window.onscroll = () =>{
    profile.classList.remove('active'); 
    searchForm.classList.remove('active');

    if(window.innerWidth<1200){
        sideBar.classList.remove('active');
        body.classList.remove('active');
    }
}



(() => {
    const counter = document.querySelectorAll('.counter'); 
    
    // Convert NodeList to Array
    const array = Array.from(counter);

    // Iterate over each counter element
    array.map((item) =>{
        let counterInnerText = item.textContent; // Parse the inner text to an integer
        item.textContent = 0; // Initialize the counter display
        let count = 1; // Starting count
        let speed = item.dataset.speed / counterInnerText; // Retrieve speed or set default

        // Increment function
        function counterUp() {
            item.textContent = count++;
            if(counterInnerText <= count){
                clearInterval(stop);
            }
        }
        const stop = setInterval(() => {
            counterUp();
        }, speed)
    })
})()

// (() => {
//     const counter = document.querySelectorAll('.counter'); 

//     // Convert NodeList to Array
//     const array = Array.from(counter);

//     // Iterate over each counter element
//     array.forEach((item) => { // Use forEach for side effects
//         let counterInnerText = parseInt(item.textContent, 10); // Parse the inner text to an integer
//         item.textContent = 0; // Initialize the counter display
//         let count = 1; // Starting count
//         let speed = item.dataset.speed 
//             ? Math.abs(item.dataset.speed / counterInnerText) 
//             : 50; // Default to a value if dataset.speed is missing or invalid

//         // Increment function
//         function counterUp() {
//             count++;
//             item.textContent = count;
//             if (count >= counterInnerText) {
//                 clearInterval(stop); // Stop when the count reaches the target
//             }
//         }

//         // Start the interval
//         const stop = setInterval(counterUp, speed);
//     });
// })();
