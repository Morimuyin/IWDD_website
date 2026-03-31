<!-- code reference: https://3dmol.csb.pitt.edu/doc/tutorial-embeddable.html-->


<script src="https://3Dmol.org/build/3Dmol-min.js"></script>     
<script src="https://3Dmol.org/build/3Dmol.ui-min.js"></script>     
 

<div style="height: 400px; width: 400px; position: relative;" class='viewer_3Dmoljs' data-pdb='2POR' data-backgroundcolor='0xffffff' data-style='stick' data-ui='true'></div>

<script>
async function mapXPtoPDB(xp_id) {
    // Step 1: map XP → UniProt
    const res = await fetch(`https://rest.uniprot.org/uniprotkb/search?query=${xp_id}&format=json`);
    const data = await res.json();

    if (!data.results.length) return null;

    const entry = data.results[0];

    // Step 2: extract PDB cross references
    const pdbRefs = entry.uniProtKBCrossReferences
        .filter(ref => ref.database === "PDB")
        .map(ref => ref.id);

    return pdbRefs;
}

const res = mapXPtoPDB('XP_002194882.1');

console.log(res);
</script>
