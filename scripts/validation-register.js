const validation = new JustValidate("#register");

validation
  .addField("#restaurant_name", [
    {
      rule: "required",
    },
  ])
  .addField("#email", [
    {
      rule: "required",
    },
    {
      rule: "email",
    },
    {
      validator: (value) => () => {
        return fetch("validate-email.php?email=" + encodeURIComponent(value))
          .then(function (response) {
            return response.json();
          })
          .then(function (json) {
            return json.available;
          });
      },
      errorMessage: "Email Already Taken"
    },
  ])
  .addField("#password", [
    {
      rule: "required",
    },
    {
      rule: "password",
    },
  ])
  .addField("#confirm-pass", [
    {
      validator: (value, fields) => {
        return value === fields["#password"].elem.value;
      },
      errorMessage: "Password should match!",
    },
  ])
  .addField("#restaurant_address", [
    {
      rule: "required",
    },
  ])
  .onSuccess((event) => {
    document.getElementById("register").submit();
  });
