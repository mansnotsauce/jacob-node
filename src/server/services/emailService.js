const nodemailer = require('nodemailer')

// create reusable transporter object using the default SMTP transport
const transporter = nodemailer.createTransport({
    host: 'email-smtp.us-west-2.amazonaws.com',
    port: 587,
    secure: false, // true for 465, false for other ports ("Secure email delivery using TLS/STARTTLS")
    auth: {
        user: 'AKIASQSCL2DWDX2JCK75',
        pass: 'BBirsFHfRP9IYXixkjQY/T6qmgTSpFdX+pX2/OCSP1Lm'
    }
})

async function sendEmail({ from, to, subject, body, painText = false }) {
    const info = await transporter.sendMail({
        from,
        to,
        subject,
        text: plainText ? body : '',
        html: plainText ? '' : body,
    })
    // if necessary, we can return fields from $info,
    // but let's not return the whole object so we don't create an implementation dependency.
}

module.exports = {
    sendEmail,
}
