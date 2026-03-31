// protein statistics
document.getElementById("btn_stats").addEventListener("click", async () => {

    const selected = getSelected(); // get selected sequences

    //post selected data to get results
    const response = await fetch("stats.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ data: selected })
    });

    const result = await response.json();
    console.log(result);

    // show outputs
    //document.getElementById("conservation_alignment").textContent = result.alignment;
    //document.getElementById("conservation_image").src = result.png_filename;
    fetch(result.stats_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("stats_result").textContent = text;
    });
});
