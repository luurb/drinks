import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const SingUp = () => {
   const [inputs, setInputs] = useState([
      {
         id: 'Nazwa barmana',
         name: 'name',
         value: '',
         error_text: '',
         error_status: false,
      },
      {
         id: 'Email',
         name: 'email',
         value: '',
         error_text: '',
         error_status: false,
      },
      {
         id: 'Hasło',
         name: 'password',
         value: '',
         error_text: '',
         error_status: false,
      },
      {
         id: 'Hasło2',
         name: 'confirm_password',
         value: '',
         error_text: '',
         error_status: false,
      },
   ]);

   const handleInput = (e) => {
      const target = e.target;
      const name = target.name;
      const value = target.value;

      setInputs(
         inputs.map((input) =>
            input.name == name ? { ...input, value: value } : input
         )
      );
   };

   const handleSubmit = (e) => {
      e.preventDefault();

      setInputs(
         inputs.map((input) => {
            if (input.value.length < 4 && input.name != 'confirm_password') {
               return {
                  ...input,
                  error_text: `${input.id} musi zawierać minimum 4 znaki`,
                  error_status: true,
               };
            }

            if (input.name == 'password') {
               const confirmPasswordInput = inputs.find(
                  (input) => input.name == 'confirm_password'
               );
               if (confirmPasswordInput.value !== input.value) {
                  return {
                     ...input,
                     error_text: 'Hasła muszą być takie same',
                     error_status: true,
                  };
               }
            }

            const validEmail =
               /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            if (input.name == 'email' && input.value.match(validEmail)) {
               return {
                  ...input,
                  error_text: 'Niepoprawny email',
                  error_status: true,
               };
            }

            const validName = /^[a-zA-Z0-9]*$/;
            if (input.name == 'name' && !input.value.match(validName)) {
               return {
                  ...input,
                  error_text: 'Niepoprawna nazwa',
                  error_status: true,
               };
            }

            return {
               ...input,
               error_text: '',
               error_status: false,
            };
         })
      );
   };

   return (
      <>
         {console.log(inputs)}
         <div className="auth__img"></div>
         <div className="auth__box">
            <div className="auth__form-box">
               <div className="auth__header">Otwórz swój bar</div>
               <form onSubmit={handleSubmit}>
                  <div className="auth__inputs-wrapper">
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="name">Nazwa barmana</label>
                           <input
                              type="text"
                              id="name"
                              name="name"
                              value={inputs.name}
                              onChange={handleInput}
                           ></input>
                        </div>
                        <div className="auth__input-box">
                           <label htmlFor="email">Email</label>
                           <input
                              type="email"
                              id="email"
                              name="email"
                              value={inputs.email}
                              onChange={handleInput}
                           ></input>
                        </div>
                     </div>
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="password">Hasło</label>
                           <input
                              type="password"
                              id="password"
                              name="password"
                              onChange={handleInput}
                           ></input>
                        </div>
                        <div className="auth__input-box">
                           <label htmlFor="confirm_password">
                              Powtórz hasło
                           </label>
                           <input
                              type="password"
                              id="confirm_password"
                              name="confirm_password"
                              onChange={handleInput}
                           ></input>
                        </div>
                     </div>
                  </div>
                  <div className="auth__text-wrapper">
                     <div className="auth__text-box">
                        Masz już swój bar?
                        <Link to="/login">
                           <span>Wejdź</span>
                        </Link>
                     </div>
                  </div>
                  <input
                     type="submit"
                     value="Stwórz bar"
                     className="auth__submit"
                  ></input>
               </form>
            </div>
         </div>
      </>
   );
};

export default SingUp;
