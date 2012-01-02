function clearDefault(el) {
  if (el.defaultValue==el.value) el.value = ""
}

function restoreDefault(el) {
  if (el.value == "" ) el.value = el.defaultValue
}

function cleartextarea(id){
if (clickedIt == false){
id.value="";
clickedIt=true;
}
} 