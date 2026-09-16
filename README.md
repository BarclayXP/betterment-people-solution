# Betterment People Solutions

The website for Betterment People Solutions, a founder-led people and HR consultancy based in London.

Live address (once published): www.betterment-consulting.com

## What's on the website

- **Home**: who we are, our four services, how we work, sector experience and client comments.
- **Services**: each service in more detail, how long it takes, and the ways clients can work with us.
- **About**: why the company exists, what we focus on, our guiding principles and company details.
- **Contact**: a booking form, our contact details and a QR code for our digital business card.

## How the booking form works

1. A visitor fills in their details, picks a consultation slot and writes a short message.
2. Slots are offered on weekdays at 10:00 AM and 2:00 PM, over the next two weeks.
3. The request is emailed to partners@betterment-consulting.com. If the visitor gives an extra email address, they get a copy of their booking.
4. Every request is also saved in a spreadsheet file inside the `storage` folder, so nothing is lost if an email fails. Its name starts with `submissions-` followed by random letters and numbers, so nobody can guess its web address. You can open it in Excel.
5. Once a slot is booked, it disappears from the list so no one else can book it.
6. One visitor can send up to 3 requests a day. This stops bots filling the inbox or booking up every slot. You can change the number in `includes/config.php`.

The saved bookings stay on the computer or web server running the site. They are deliberately never added to this repository, because they contain people's personal details.

## Viewing the website on your computer

1. Make sure WampServer is installed (it provides PHP, which the website needs).
2. Double-click **start-site.bat** in this folder.
3. Your browser opens the website at http://127.0.0.1:8081/
4. Keep the black window open while you look around. Close it to stop the website.

Emails are not sent when the site runs on your own computer, but bookings are still saved to the `storage` folder.

## Where to change things

| To change... | Edit this file |
| --- | --- |
| Contact email, phone number or the address emails are sent from | `includes/config.php` |
| Home page wording | `index.php` |
| Services page wording | `services.php` |
| About page wording | `about.php` |
| Contact page wording | `contact.php` |
| The "Book a Consultation" banner at the bottom of pages | `partials/cta.php` |
| Menu at the top, or the footer at the bottom | `partials/header.php` and `partials/footer.php` |
| Colours, spacing and fonts | `assets/css/style.css` |

When editing wording, change only the words themselves. Leave the quote marks, brackets and other symbols around them exactly as they are.

## Before the website goes live

- [ ] Choose web hosting that supports PHP 7.4 or newer (most standard hosting plans do). Apache or LiteSpeed hosting is best, because the site's protection files (`.htaccess`) work there.
- [ ] Turn on HTTPS (the padlock) in your hosting control panel, including the option to always redirect to HTTPS.
- [ ] Check the email addresses in `includes/config.php`. The "from" address should be on your own domain, so emails don't end up in spam.
- [ ] Send a test booking on the live site and confirm the email arrives.
- [ ] Make sure the `storage` folder on the server can be written to, so bookings can be saved.
- [ ] Check the private folders are blocked: open www.betterment-consulting.com/storage/.htaccess and www.betterment-consulting.com/includes/config.php in a browser. Both must show an error page, not a file.
- [ ] Add a privacy notice explaining how you use the details people send through the form, and how long you keep them (a UK GDPR requirement).
- [ ] Confirm the client comments on the home page are real quotes you have permission to use.
- [ ] Upload only the website files. Leave out the `.git` folder, `Website Content.pdf`, `Website Content.txt`, `start-site.bat`, `router.php` and this README.

## Keeping the website secure

The site already includes these protections, so there is nothing to switch on:

- **Booking form:** blocks forged submissions and spam bots, checks everything people type, and limits how many requests one visitor can send.
- **Personal data:** saved bookings are kept in a private folder with an unguessable file name, and are never added to this repository.
- **Browser protection:** the site tells browsers to load only its own files, never to show it inside another website, and to use a secure connection once HTTPS is on.
- **No third parties:** fonts are served from the website itself, so visitors' details are not shared with Google.
- **Error messages:** technical details are never shown to visitors on the live site.

Good habits:

- Download the bookings file regularly, then delete old bookings you no longer need.
- Keep your hosting account and email password strong, and turn on two-step login where offered.
- Ask your host to keep PHP up to date.

## Other files in this folder

- `Website Content.pdf` and `Website Content.txt`: the original content brief for the website.
- `better.png` and `better1.png`: the original logo files. Trimmed versions used on the site are in `assets/img`.
- `qr.png`: the QR code for the digital business card.
