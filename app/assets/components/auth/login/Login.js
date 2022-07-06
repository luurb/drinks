import React, { useState, useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import AuthContext from '../AuthContext';

const Login = () => {
   const [inputs, setInputs] = useState([
      {
         id: 'Nazwa',
         name: 'name',
         value: '',
      },
      {
         id: 'Hasło',
         name: 'password',
         value: '',
      },
   ]);
   const [errors, setErrors] = useState({
      name: {
         text: '',
         active: false,
      },
      password: {
         text: '',
         active: false,
      },
      login: {
         text: '',
         active: false,
      },
   });
   const user = useContext(AuthContext);
   const navigate = useNavigate();

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
      let errorsCopy = errors;
      inputs.forEach((input) => {
         if (input.value.length < 4) {
            errorsCopy[input.name] = {
               text: `${input.id} musi zawierać minimum 4 znaki`,
               active: true,
            };

            return;
         }

         const validName = /^[a-zA-Z0-9]*$/;
         if (input.name == 'name' && !input.value.match(validName)) {
            errorsCopy[input.name] = {
               text: 'Nazwa zawiera niedozwolone znaki',
               active: true,
            };

            return;
         }

         errorsCopy[input.name] = {
            text: '',
            active: false,
         };
      });
      setErrors({ ...errorsCopy, login: { text: '', active: false } });
      login();
   };

   const login = async () => {
      if (Object.values(errors).some((value) => value.active)) return;

      const userName = inputs.find((input) => input.name == 'name').value;
      const password = inputs.find((input) => input.name == 'password').value;

      try {
         const response = await axios.post('/api/login', {
            username: userName,
            password: password,
         });
         console.log(response);

         if (response.status === 204) {
            navigate('/dashboard', { replace: true });
         }
      } catch (error) {
         const response = error.response;
         console.log(response);
         response.status == 401 &&
            (response.data.error = 'Nieprawidłowe dane');
         response.data.error &&
            setErrors({
               ...errors,
               login: { text: response.data.error, active: true },
            });
      }
   };

   return (
      <>
         <div className="auth__img"></div>
         <div className="auth__box">
            <div className="auth__form-box">
               <div className="auth__header">Wejdź do baru</div>
               {errors.login.active && (
                  <div className="auth__error">{errors.login.text}</div>
               )}
               <form onSubmit={handleSubmit}>
                  <div className="auth__input-box">
                     <label htmlFor="name">Nazwa barmana</label>
                     <input
                        type="text"
                        id="name"
                        name="name"
                        value={inputs.name}
                        onChange={handleInput}
                     ></input>
                     {errors.name.active && (
                        <div className="auth__error">{errors.name.text}</div>
                     )}
                  </div>
                  <div className="auth__input-box">
                     <label htmlFor="password">Hasło</label>
                     <input
                        type="password"
                        id="password"
                        name="password"
                        onChange={handleInput}
                     ></input>
                     {errors.password.active && (
                        <div className="auth__error">
                           {errors.password.text}
                        </div>
                     )}
                  </div>
                  <div className="auth__text-wrapper">
                     <div className="auth__text-box">
                        Pierwszy raz?
                        <Link to="/singup">
                           <span>Otwórz bar</span>
                        </Link>
                     </div>
                     <div className="auth__text-box">
                        Nie pamiętasz hasła?
                        <span>Resetuj</span>
                     </div>
                  </div>
                  <input
                     type="submit"
                     value="Wejdź"
                     className="auth__submit"
                  ></input>
               </form>
            </div>
         </div>
      </>
   );
};

export default Login;
