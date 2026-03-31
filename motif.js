// motif analysis
document.getElementById("btn_motif").addEventListener("click", async () => {

    const selected = getSelected(); // get selected sequences

    //post selected data to get results
    const response = await fetch("motif.php", {
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
    fetch(result.motif_filename)
    .then(res => res.text())
    .then(text => {
        document.getElementById("motif_result").textContent = text;
    });
});
