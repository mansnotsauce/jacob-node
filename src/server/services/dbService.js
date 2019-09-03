const run = (sql, params = {}) => new Promise((resolve, reject) => {
    throw new Error("NOT YET IMPLEMEBTED: RUN")
    db.run(sql, params, function cb(err) {
        err ? reject(err) : resolve({
            insertedId: this.lastID || null,
            rowsChangedCount: this.count || 0,
        })
    })
})
const getRow = (sql, params = {}) => new Promise((resolve, reject) => {
    throw new Error("NOT YET IMPLEMENTED: GETROW")
    db.get(sql, params, function cb(err, row) {
        err ? reject(err) : resolve(row)
    })
    
})
const getRows = (sql, params = {}) => new Promise((resolve, reject) => {
    throw new Error("NOT YET IMPLEMENTED: GET ROWS")
    db.all(sql, params, function cb(err, rows) {
        err ? reject(err) : resolve(rows)
    })
})

module.exports = {
    run,
    getRow,
    getRows,
}
