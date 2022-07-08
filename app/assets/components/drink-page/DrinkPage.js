import React, { useEffect, useState } from 'react';
import Nav from '../nav/Nav';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import FullDrink from './FullDrink';

const DrinkPage = () => {
   const [drink, setDrink] = useState();
   const params = useParams();

   useEffect(() => {
      const uri = '/api/drinks/' + params.drinkId;

      (async () => {
         try {
            const response = await axios.get(uri);
            if (response.status == 200) {
               setDrink(response.data);
            }
         } catch (error) {
            console.log(error);
         }
      })();
   }, []);

   return (
      <>
         <Nav />
         {drink && <FullDrink drink={drink} />}
      </>
   );
};

export default DrinkPage;
