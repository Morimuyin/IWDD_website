// A js function used for get the selected sequences
// generated with the help of ChatGPT

function getSelected() {
    const checked = document.querySelectorAll(".row-select:checked");

    const selectedData = Array.from(checked).map(cb => ({
        id: cb.dataset.id,
        name: cb.dataset.name,
        sequence: cb.dataset.sequence
    }));

    console.log("Selected full data:", selectedData);
    return selectedData;
}

document.getElementById("btn_select").addEventListener("click", function () {
    getSelected();
});
