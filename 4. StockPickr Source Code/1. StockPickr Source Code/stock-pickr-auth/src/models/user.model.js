const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/mysql.connection');

const User = sequelize.define('User', {
    name: {
        type: DataTypes.STRING(40),
        allowNull: false
    },
    email: {
        type: DataTypes.STRING(50),
        allowNull: false,
        unique: true
    },
    password: {
        type: DataTypes.STRING(70),
        allowNull: false
    },
    token: {
        type: DataTypes.STRING,
        allowNull: true
    }
}, {
    tableName: 'users'
});

module.exports = User;