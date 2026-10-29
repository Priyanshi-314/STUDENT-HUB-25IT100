"use strict";
const $ = id => document.getElementById(id);
const fields=['full_name','email','mobile','program','study_year','gender','status'];
const ids={'full_name':'full-name','email':'email','mobile':'mobile','program':'program','study_year':'study-year','gender':'gender','status':'status'};
let records=[];
function message(text){$('message').textContent=text;}
async function request(payload=null,q=''){
 const options=payload===null?{}:{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-Token':window.STUDENTHUB_CSRF},body:JSON.stringify(payload)};
 const response=await fetch('api.php'+(payload===null?'?q='+encodeURIComponent(q):''),options);
 const data=await response.json();if(!response.ok||!data.ok)throw Error(data.error||'Request failed');return data;
}
function cell(tr,text){const td=document.createElement('td');td.textContent=String(text??'');tr.append(td);return td;}
function render(){const body=$('students-body');body.replaceChildren();if(!records.length){const tr=document.createElement('tr');const td=cell(tr,'No matching students');td.colSpan=7;body.append(tr);return;}
 for(const item of records){const tr=document.createElement('tr');cell(tr,item.student_id);cell(tr,item.full_name+' / '+item.email);cell(tr,item.mobile);cell(tr,item.program);cell(tr,item.study_year);cell(tr,item.status);const actions=document.createElement('td');actions.className='p13-actions';for(const action of ['Edit','Delete']){const button=document.createElement('button');button.type='button';button.textContent=action;button.addEventListener('click',()=>action==='Edit'?edit(item):remove(item));actions.append(button);}tr.append(actions);body.append(tr);}}
async function load(){try{const data=await request(null,$('search').value.trim());records=data.students;render();message(records.length+' student(s) loaded. No page reload.');}catch(e){message(e.message);}}
function reset(){ $('student-form').reset();$('student-id').value='';$('form-heading').textContent='Add student';$('save-button').textContent='Add student';}
function edit(item){$('student-id').value=item.student_id;for(const field of fields)$(ids[field]).value=item[field]??'';$('form-heading').textContent='Edit student #'+item.student_id;$('save-button').textContent='Save changes';$('student-form').scrollIntoView({behavior:'smooth'});}
async function remove(item){if(!confirm('Delete '+item.full_name+'? Students with registrations cannot be deleted.'))return;try{const result=await request({action:'delete',student_id:Number(item.student_id)});await load();message(result.message);}catch(e){message(e.message);}}
$('student-form').addEventListener('submit',async e=>{e.preventDefault();if(!e.target.reportValidity())return;const payload={action:$('student-id').value?'update':'create'};if($('student-id').value)payload.student_id=Number($('student-id').value);for(const field of fields)payload[field]=$(ids[field]).value;payload.study_year=Number(payload.study_year);$('save-button').disabled=true;try{const result=await request(payload);reset();await load();message(result.message);}catch(err){message(err.message);}finally{$('save-button').disabled=false;}});
$('reset-button').addEventListener('click',reset);
let searchTimer;$('search').addEventListener('input',()=>{clearTimeout(searchTimer);searchTimer=setTimeout(load,250);});load();
