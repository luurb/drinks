import React from 'react';
import Search from './search/Search'
import DrinksBox from './drinks/DrinksBox'

const Box = () => {
    return (
        <div className="drinks-box">
            <Search />
            <DrinksBox />
        </div>
    )
};

export default Box;
