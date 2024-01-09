const validation = new JustValidate("#employee-form");

validation
    .addField("#email",[
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
    .addField("#password",[
        {
            rule: "required",
        },
        {
            rule: "password",
        },
    ])
    .addField("#confirm-pass",[
        {
            validator:(value,fields)=>{
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Password should match!",
        },
    ])
    .addField("#firstName",[
        {
            rule: "required",
        },
    ])
    .addField("#lastName",[
        {
            rule: "required",
        },
    ])
    .addField("#empRoles",[
        {
            rule: "required",
        },
    ])
    .addField("#empDate",[
        {
            rule: "required",
        },
    ])
    .addField("#empNo",[
        {
            rule: "number",
        },
        {
            rule: "required",
        },
        
    ])
    .addField("#empSalary",[
        {
            rule: "number",
        },
        {
            rule: "required",
        },
        
    ])
    .onSuccess((event) => {
        document.getElementById("employee-insert").submit();
    });
    