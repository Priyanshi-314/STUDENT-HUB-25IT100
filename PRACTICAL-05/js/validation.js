// Practical 5: accessible client-side registration validation.
const form = document.querySelector("#registrationForm");
const success = document.querySelector("#formSuccess");
const password = document.querySelector("#password");
const strength = document.querySelector("#passwordStrength");
const genderOptions = [...form.querySelectorAll('input[name="gender"]')];
const rules = {
  name: ["#name", "#nameError", value => /^[\p{L}][\p{L}\p{M} .'-]{1,59}$/u.test(value.trim()) || "Enter your name using at least 2 letters."],
  email: ["#email", "#emailError", value => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value.trim()) || "Enter a valid email address, such as name@example.com."],
  phone: ["#phone", "#phoneError", value => /^[6-9]\d{9}$/.test(value.trim()) || "Enter a 10-digit Indian mobile number starting with 6, 7, 8, or 9."],
  course: ["#course", "#courseError", value => Boolean(value) || "Choose your course."],
  year: ["#year", "#yearError", value => Boolean(value) || "Choose your current year of study."],
  password: ["#password", "#passwordError", value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,}$/.test(value) || "Use at least 8 characters with uppercase, lowercase, a number, and a symbol."],
  confirm: ["#confirm", "#confirmError", value => value.length > 0 && value === password.value || "Enter the same password again."],
  terms: ["#terms", "#formNotice", checked => checked || "Please accept the demonstration form notice to continue."]
};
const fields = Object.fromEntries(Object.entries(rules).map(([name, [input, error, validate]]) => [name, { input: document.querySelector(input), error: document.querySelector(error), validate }]));

function validateField(name) {
  const field = fields[name];
  const value = field.input.type === "checkbox" ? field.input.checked : field.input.value;
  const result = field.validate(value);
  const message = result === true ? "" : result;
  field.error.textContent = message;
  field.input.setAttribute("aria-invalid", String(Boolean(message)));
  return !message;
}

function updateStrength() {
  const value = password.value;
  if (!value) {
    strength.textContent = "Password strength will appear here";
    strength.removeAttribute("data-level");
    return;
  }
  const checks = [value.length >= 8, /[a-z]/.test(value) && /[A-Z]/.test(value), /\d/.test(value), /[^A-Za-z0-9\s]/.test(value)];
  const score = checks.filter(Boolean).length;
  const level = score <= 2 ? "weak" : score === 3 ? "fair" : "strong";
  strength.dataset.level = level;
  strength.textContent = "Password strength: " + level + " (" + score + " of 4 checks)";
}

Object.keys(fields).forEach(name => {
  const input = fields[name].input;
  input.addEventListener("blur", () => validateField(name));
  input.addEventListener("input", () => {
    if (fields[name].error.textContent) validateField(name);
    success.textContent = "";
  });
  input.addEventListener("change", () => validateField(name));
});
password.addEventListener("input", () => {
  updateStrength();
  if (fields.confirm.input.value) validateField("confirm");
});
genderOptions.forEach(option => option.addEventListener("change", () => {
  document.querySelector("#genderError").textContent = "";
  document.querySelector("#genderError").parentElement.querySelector("fieldset").removeAttribute("aria-invalid");
  success.textContent = "";
}));

form.addEventListener("submit", event => {
  event.preventDefault();
  success.textContent = "";
  let valid = Object.keys(fields).map(validateField).every(Boolean);
  const genderError = document.querySelector("#genderError");
  const genderFieldset = genderError.parentElement.querySelector("fieldset");
  if (!genderOptions.some(option => option.checked)) {
    genderError.textContent = "Choose an option or select Prefer not to say.";
    genderFieldset.setAttribute("aria-invalid", "true");
    valid = false;
  } else {
    genderError.textContent = "";
    genderFieldset.removeAttribute("aria-invalid");
  }
  if (!valid) {
    (form.querySelector('[aria-invalid="true"]')?.matches("input, select, textarea") ? form.querySelector('[aria-invalid="true"]') : genderOptions[0]).focus();
    return;
  }
  form.submit();
});
form.addEventListener("reset", () => window.setTimeout(() => {
  Object.values(fields).forEach(field => { field.error.textContent = ""; field.input.removeAttribute("aria-invalid"); });
  document.querySelector("#genderError").textContent = "";
  document.querySelector("#genderError").parentElement.querySelector("fieldset").removeAttribute("aria-invalid");
  success.textContent = "";
  updateStrength();
}, 0));
