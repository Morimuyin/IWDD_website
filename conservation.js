// conservation analysis
document.getElementById("btn_conservation").addEventListener("click", async () => {

    const selected = getSelected(); // get selected sequences

    //post selected data to get results
    const response = await fetch("conservation.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ data: selected })
    });

    const result = await response.json();
    console.log(result.image);

    // show outputs
    document.getElementById("conservation_alignment").textContent = result.alignment;
    document.getElementById("conservation_image").src = result.image;
});
