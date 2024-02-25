document.addEventListener("DOMContentLoaded", () => {
  const userType = document.getElementById("user-type");
  const departmentContainer = document.getElementById("departmentContainer");
  const form = document.getElementById("sign-in-form");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm-password");

  userType.addEventListener("change", () => {
      if (userType.value === "principal") {
          departmentContainer.style.display = "none";
      } else {
          departmentContainer.style.display = "block";
      }
  });

  form.addEventListener("submit", (e) => {
      e.preventDefault();
      
      const passwordValue = password.value;
      const confirmPasswordValue = confirmPassword.value;
      const strongPasswordRegex = /^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;

      if (!strongPasswordRegex.test(passwordValue)) {
          alert("Password must be at least 8 characters long, contain at least 1 alphabet, 1 number, and 1 symbol.");
          return;
      }
  
      if (passwordValue !== confirmPasswordValue) {
          alert("Passwords do not match.");
          return;
      }
  
      form.submit();
  });
});

  
