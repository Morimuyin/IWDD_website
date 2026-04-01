async function getquery(event){
//stop the default form submission
     event.preventDefault();
    console.log("function started");  
	// query to get the maximun 20 results
    // get elements
    const btn = document.getElementById("searchbtn");
    const output = document.getElementById("results");
    const family = document.getElementById("family").value;
    const taxonomy = document.getElementById("taxonomy").value;
    const errormes = document.getElementById("error_message"); 
    const loading = document.getElementById("loading");

    console.log("family:",family);
    console.log("taxonomy:",taxonomy);
    loading.style.display = "block";

    try{
            const response = await fetch("query.php",{
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: `family=${family}&taxonomy=${taxonomy}`
            });

	    // check result
	    //const text = await response.text();
	   //console.log("respone text:", text); 
    // display the results in the table
    const data = await response.json();


/* test using example_dataset.json
    const response = await fetch("example_dataset.json");
    const data = await response.json();
*/

/* test js array	
    const data = [
  {id:"test", name:"demo", length:3, sequence:"AAA"}
];
*/
	
    output.innerHTML = "";


    data.forEach(item => {
        console.log("item:",item);
	const row = document.createElement("tr");
	row.innerHTML = `
            <td>
            <input type="checkbox"
               class="row-select"
               data-id="${item.id}"
               data-name="${item.name}"
               data-sequence="${item.sequence}">
            </td>
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.length}</td>
            <td style="max-width:500px; word-break:break-all;">
                ${item.sequence}
            </td>
	`;
	output.appendChild(row);
    }) 
 

/* test DOM
	const row = document.createElement("tr");
        row.innerHTML = `
            <td>id1</td>
            <td>name1</td>
            <td>length1</td>
            <td style="max-width:300px; word-break:break-all;">
                ASASASAS
            </td>
        `;
        output.appendChild(row);
 */

   } catch(err) {
    errormes.innerHTML = 'Error';
   console.error("FULL ERROR:", err);
   }

    loading.style.display = "none";

/* test code for event
	//stop the default form submission
	event.preventDefault();
	const result_t = document.getElementById("result_test");
	result_t.innerHTML = "successful";
*/

}

document.getElementById("searchform").addEventListener("submit",getquery)
