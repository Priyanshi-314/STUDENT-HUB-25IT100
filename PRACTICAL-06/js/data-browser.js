// Practical 6: fetch, search, filter, sort, and paginate JSON collections.
const collection = document.querySelector("#data-type"), search = document.querySelector("#data-search");
const filter = document.querySelector("#data-filter"), filterLabel = document.querySelector("#filter-label");
const sort = document.querySelector("#data-sort"), results = document.querySelector("#data-results");
const statusText = document.querySelector("#data-status"), errorPanel = document.querySelector("#data-error");
const pageStatus = document.querySelector("#page-status"), previous = document.querySelector("#previous-page"), next = document.querySelector("#next-page");
const pageSize = 6;
let records = [], page = 1, requestNumber = 0;
const sources = {
  events: { file: "events.json", category: "category", label: "All categories", singular: "event" },
  students: { file: "students.json", category: "program", label: "All programs", singular: "student profile" },
  faqs: { file: "faqs.json", category: "category", label: "All categories", singular: "FAQ" }
};

function chip(text) { const item = document.createElement("span"); item.className = "data-chip"; item.textContent = text; return item; }
function cardFor(record, type) {
  const card = document.createElement("article"); card.className = "data-card";
  const title = document.createElement("h3"), meta = document.createElement("div"); meta.className = "data-meta";
  title.textContent = type === "faqs" ? record.question : type === "students" ? record.name : record.title;
  if (type === "events") {
    meta.append(chip(record.category), chip(record.date), chip(record.location));
    const description = document.createElement("p"); description.textContent = record.description;
    card.append(title, meta, description);
  } else if (type === "students") {
    meta.append(chip(record.program), chip("Year " + record.year));
    const email = document.createElement("a"); email.href = "mailto:" + record.email; email.textContent = record.email;
    card.append(title, meta, email);
  } else {
    meta.append(chip(record.category));
    const details = document.createElement("details"), summary = document.createElement("summary"), answer = document.createElement("p");
    summary.textContent = "Read answer"; answer.textContent = record.answer; details.append(summary, answer); card.append(title, meta, details);
  }
  return card;
}
function setOptions() {
  const type = collection.value, config = sources[type];
  filterLabel.textContent = type === "students" ? "Filter by program" : "Filter by category";
  const values = [...new Set(records.map(item => item[config.category]))].sort((a,b) => a.localeCompare(b));
  filter.replaceChildren(new Option(config.label, ""), ...values.map(value => new Option(value, value)));
  const order = type === "events" ? [["alpha-asc","Title: A to Z"],["alpha-desc","Title: Z to A"],["date-asc","Date: earliest first"],["date-desc","Date: latest first"]] : [["alpha-asc","A to Z"],["alpha-desc","Z to A"]];
  sort.replaceChildren(...order.map(([value,label]) => new Option(label,value)));
}
function visibleRecords() {
  const type = collection.value, config = sources[type], term = search.value.trim().toLocaleLowerCase();
  let found = records.filter(item => (!filter.value || item[config.category] === filter.value) && (!term || Object.values(item).join(" ").toLocaleLowerCase().includes(term)));
  const descending = sort.value.endsWith("desc") ? -1 : 1;
  if (type === "events" && sort.value.startsWith("date")) found.sort((a,b) => a.date.localeCompare(b.date) * descending);
  else { const key = type === "events" ? "title" : type === "students" ? "name" : "question"; found.sort((a,b) => a[key].localeCompare(b[key]) * descending); }
  return found;
}
function render() {
  const found = visibleRecords(), pages = Math.max(1, Math.ceil(found.length / pageSize));
  page = Math.min(page, pages); const offset = (page - 1) * pageSize;
  results.replaceChildren(...found.slice(offset, offset + pageSize).map(item => cardFor(item, collection.value)));
  statusText.textContent = found.length + " " + sources[collection.value].singular + (found.length === 1 ? "" : "s") + " found";
  if (!found.length) { const message = document.createElement("p"); message.textContent = "No results match. Change or clear your search and filter."; results.append(message); }
  pageStatus.textContent = "Page " + page + " of " + pages;
  previous.disabled = page === 1 || !found.length; next.disabled = page === pages || !found.length;
}
async function loadData() {
  const thisRequest = ++requestNumber, type = collection.value;
  results.replaceChildren(); errorPanel.hidden = true; statusText.textContent = "Loading " + type + "…";
  try {
    const response = await fetch("../PRACTICAL-06/data/" + sources[type].file, { headers: { Accept: "application/json" } });
    if (!response.ok) throw new Error("HTTP " + response.status);
    const data = await response.json();
    if (!Array.isArray(data) || data.length < 15) throw new Error("Expected at least 15 JSON records.");
    if (thisRequest !== requestNumber) return;
    records = data; page = 1; search.value = ""; setOptions(); render();
  } catch (error) {
    if (thisRequest !== requestNumber) return;
    console.error("StudentHub JSON load error:", error); records = [];
    statusText.textContent = "The data could not be loaded."; pageStatus.textContent = "Page 1 of 1";
    previous.disabled = true; next.disabled = true; errorPanel.hidden = false;
  }
}
collection.addEventListener("change", loadData);
search.addEventListener("input", () => { page = 1; render(); });
filter.addEventListener("change", () => { page = 1; render(); });
sort.addEventListener("change", () => { page = 1; render(); });
previous.addEventListener("click", () => { page -= 1; render(); });
next.addEventListener("click", () => { page += 1; render(); });
document.querySelector("#retry-data").addEventListener("click", loadData);
loadData();
