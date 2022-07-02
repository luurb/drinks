import React from 'react';

const LoadingDrink = ({ margin }) => {
   return (
      <div className="drink__glass" style={{ marginTop: margin }}>
         <div className="drink__glass-fill"></div>
      </div>
   );
};

export default LoadingDrink;
