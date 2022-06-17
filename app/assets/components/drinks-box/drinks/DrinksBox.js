import React from 'react';
import Drink from './Drink';
import SortBox from './SortBox';


const DrinksBox = () => {
  return (
    <div className="drinks">
      <div className="drinks__top">
        <span className="drinks__counter">Znaleziono 15 drinków</span>
        <SortBox />
      </div>
      <Drink />
    </div>
  );
};

export default DrinksBox;
